<?php

namespace App\Controllers\Dashboard;

use Rakit\Validation\Validator;
use Carbon\Carbon;
use App\Enum\DanceSessionEnum;
use App\Repositories\DanceRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ArtistRepository;

class DanceEventController extends DashboardController
{
    private DanceRepository $danceRepository;
    private LocationRepository $locationRepository;
    private ArtistRepository $artistRepository;

    public function __construct()
    {
        parent::__construct();
        $this->danceRepository = new DanceRepository();
        $this->locationRepository = new LocationRepository();
        $this->artistRepository = new ArtistRepository();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToDanceEvents();
        }

        return $this->renderPage(
            'dance_events',
            [
                'events' => $this->danceRepository->getAllEvents(),
                'status' => $this->getStatus(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
    }

    public function deleteDanceEvent(): void 
    {
        $eventId = $_POST['id'] ?? null;
        if (!$eventId) $this->redirectToDanceEvents(false, 'Invalid dance event ID.');

        $this->danceRepository->detachArtistsFromEvent($eventId);
        $success = (bool) $this->danceRepository->deleteDanceEvent($eventId);
        $this->redirectToDanceEvents($success, $success ? 'Dance event deleted successfully.' : 'Failed to delete dance event.');
    }

    public function editDanceEvent(): string
    {
        $eventId = $_GET['id'] ?? null;
        if (!$eventId) $this->redirectToDanceEvents(false, 'Invalid dance event ID.');

        $event = $this->danceRepository->getEventById($eventId);
        if (!$event) $this->redirectToDanceEvents(false, 'Dance event not found.');

        $formData = [
            'id' => $event->id,
            'location_id' => $event->location_id,
            'start_date' => Carbon::parse($event->start_date)->format('Y-m-d'),
            'start_time' => Carbon::parse($event->start_time)->format('H:i'),
            'end_date' => Carbon::parse($event->end_date)->format('Y-m-d'),
            'end_time' => Carbon::parse($event->end_time)->format('H:i'),
            'session' => $event->session->value,
            'total_tickets' => $event->total_tickets,
            'price' => $event->price,
            'vat' => $event->vat,
            'selected_artists' => $this->danceRepository->getEventArtists($eventId),
        ];
        
        return $this->showDanceEventForm('edit', $formData);
    }

    public function editDanceEventPost(): void
    {
        try {
            $eventId = $_POST['id'] ?? null;
            if (!$eventId) throw new \Exception('Invalid dance event ID.');

            $existingEvent = $this->danceRepository->getEventById($eventId);
            if (!$existingEvent) throw new \Exception('Dance event not found.');

            $validator = new Validator();
            $validation = $validator->validate(
                $_POST,
                [
                    'location_id' => 'required|integer',
                    'start_date' => 'required|date:Y-m-d',
                    'start_time' => 'required|date:H:i',
                    'end_date' => 'required|date:Y-m-d',
                    'end_time' => 'required|date:H:i',
                    'session' => 'required',
                    'total_tickets' => 'required|integer',
                    'price' => 'required|numeric',
                    'vat' => 'required|numeric',
                ]
            );

            if ($validation->fails()) {
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $existingEvent->location_id = $_POST['location_id'];
            $existingEvent->start_date = Carbon::parse($_POST['start_date']);
            $existingEvent->start_time = Carbon::parse($_POST['start_time']);
            $existingEvent->end_date = Carbon::parse($_POST['end_date']);
            $existingEvent->end_time = Carbon::parse($_POST['end_time']);
            $existingEvent->session = DanceSessionEnum::from($_POST['session']);
            $existingEvent->total_tickets = $_POST['total_tickets'];
            $existingEvent->price = $_POST['price'];
            $existingEvent->vat = $_POST['vat'];

            $this->danceRepository->updateDanceEvent($existingEvent);

            $this->danceRepository->detachArtistsFromEvent($eventId);
            if (!empty($_POST['artist_ids']) && is_array($_POST['artist_ids'])) {
                $this->danceRepository->attachArtistsToEvent($eventId, $_POST['artist_ids']);
            }

            $this->redirectToDanceEvents(true, 'Dance event updated successfully.');
        } catch (\Exception $e) {
            $this->showDanceEventForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createDanceEvent(): string
    {
        return $this->showDanceEventForm();
    }

    public function createDanceEventPost(): string
    {
        try {
            $validator = new Validator();
            $validation = $validator->validate(
                $_POST,
                [
                    'location_id' => 'required|integer',
                    'start_date' => 'required|date:Y-m-d',
                    'start_time' => 'required|date:H:i',
                    'end_date' => 'required|date:Y-m-d',
                    'end_time' => 'required|date:H:i',
                    'session' => 'required',
                    'total_tickets' => 'required|integer',
                    'price' => 'required|numeric',
                    'vat' => 'required|numeric',
                ]
            );

            if ($validation->fails()) {
                return $this->showDanceEventForm('create', $_POST, $validation->errors()->all());
            }

            $eventData = array_intersect_key($_POST, array_flip([
                'location_id',
                'start_date',
                'start_time',
                'end_date',
                'end_time',
                'session',
                'total_tickets',
                'price',
                'vat',
            ]));

            $newEvent = $this->danceRepository->createDanceEvent($eventData);

            if (!empty($_POST['artist_ids']) && is_array($_POST['artist_ids'])) {
                $this->danceRepository->attachArtistsToEvent($newEvent->id, $_POST['artist_ids']);
            }

            $this->redirectToDanceEvents(true, 'Dance event created successfully.');
        } catch (\Exception $e) {
            return $this->showDanceEventForm('create', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    private function redirectToDanceEvents(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('events/dance', $success, $message);
    }

    public function showDanceEventForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'dance_event',
            $mode,
            $formData,
            $errors,
            $status,
            [
                'locations' => $this->locationRepository->getAllLocations(),
                'sessions' => DanceSessionEnum::cases(),
                'artists' => $this->artistRepository->getAllArtists(),
            ]
        );
    }

    public function exportDanceEvents(): void 
    {
        $eventsArray = $this->danceRepository->getAllEvents();

        $events = array_map(fn($row) => (object) $row, $eventsArray);

        $columns = [
            'event_id' => 'Event ID',
            'start_date' => 'Start Date',
            'start_time' => 'Start Time',
            'end_date' => 'End Date',
            'end_time' => 'End Time',
            'location_name' => 'Location',
            'artist_names' => 'Artists',
            'session' => 'Session',
            'duration' => 'Duration',
            'tickets_available' => 'Tickets Available',
            'price' => 'Total Price',
        ];

        $this->exportToCsv('dance_events', $events, $columns);
    }
}