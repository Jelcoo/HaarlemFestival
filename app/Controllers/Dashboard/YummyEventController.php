<?php

namespace App\Controllers\Dashboard;

use Carbon\Carbon;
use Rakit\Validation\Validator;
use App\Repositories\YummyRepository;
use App\Repositories\LocationRepository;

class YummyEventController extends DashboardController
{
    private LocationRepository $locationRepository;
    private YummyRepository $yummyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->locationRepository = new LocationRepository();
        $this->yummyRepository = new YummyRepository();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToYummyEvents();
        }

        return $this->renderPage(
            'yummy_events',
            [
                'events' => $this->yummyRepository->getSortedEvents($searchQuery, $sortColumn, $sortDirection),
                'restaurants' => $this->locationRepository->getYummyLocations(),
                'status' => $this->getStatus(),
                'columns' => $this->getColumns(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
    }

    public function deleteYummyEvent(): void
    {
        $eventId = $_POST['id'] ?? null;
        if (!$eventId) {
            $this->redirectToYummyEvents(false, 'Invalid yummy event ID.');
        }

        try {
            $success = (bool) $this->yummyRepository->deleteEvent($eventId);
            $this->redirectToYummyEvents($success, $success ? 'Yummy event deleted successfully.' : 'Failed to delete yummy event.');
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000] => integrity constraint violation
                $this->redirectToYummyEvents(false, 'Cannot delete this event because it has tickets connected.');
            } else {
                $this->redirectToYummyEvents(false, 'Database error: ' . $e->getMessage());
            }
        }
    }

    public function editYummyEvent(): string
    {
        $eventId = $_GET['id'] ?? null;
        if (!$eventId) {
            $this->redirectToYummyEvents(false, 'Invalid yummy event ID.');
        }

        $event = $this->yummyRepository->getEventById($eventId);
        if (!$event) {
            $this->redirectToYummyEvents(false, 'Yummy event not found.');
        }

        $formData = [
            'id' => $event->id,
            'restaurant_id' => $event->restaurant_id,
            'total_seats' => $event->total_seats,
            'kids_price' => $event->kids_price,
            'adult_price' => $event->adult_price,
            'reservation_cost' => $event->reservation_cost,
            'vat' => $event->vat,
            'start_date' => Carbon::parse($event->start_date)->format('Y-m-d'),
            'start_time' => Carbon::parse($event->start_time)->format('H:i'),
            'end_date' => Carbon::parse($event->end_date)->format('Y-m-d'),
            'end_time' => Carbon::parse($event->end_time)->format('H:i'),
            'has_tickets' => $this->yummyRepository->eventHasTickets($eventId),
        ];

        return $this->showYummyEventForm('edit', $formData);
    }

    public function editYummyEventPost(): void
    {
        try {
            $eventId = $_POST['id'] ?? null;
            if (!$eventId) {
                $this->redirectToYummyEvents(false, 'Invalid yummy event ID.');
            }

            $existingEvent = $this->yummyRepository->getEventById($eventId);
            if (!$existingEvent) {
                $this->redirectToYummyEvents(false, 'Yummy event not found.');
            }

            $validationRules = [
                'restaurant_id' => 'required|numeric',
                'start_time' => 'required',
                'start_date' => 'required',
                'end_time' => 'required',
                'end_date' => 'required',
            ];

            if (!isset($_POST['has_tickets']) || $_POST['has_tickets'] === '0') {
                $validationRules['total_seats'] = 'required|numeric';
                $validationRules['kids_price'] = 'required|numeric';
                $validationRules['adult_price'] = 'required|numeric';
                $validationRules['reservation_cost'] = 'required|numeric';
                $validationRules['vat'] = 'required|numeric';
            }

            $validator = new Validator();
            $validation = $validator->validate($_POST, $validationRules);

            if ($validation->fails()) {
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $existingEvent->restaurant_id = $_POST['restaurant_id'];
            $existingEvent->total_seats = $_POST['total_seats'];
            $existingEvent->kids_price = $_POST['kids_price'];
            $existingEvent->adult_price = $_POST['adult_price'];
            $existingEvent->reservation_cost = $_POST['reservation_cost'];
            $existingEvent->vat = $_POST['vat'] / 100;
            $existingEvent->start_date = Carbon::parse($_POST['start_date']);
            $existingEvent->start_time = Carbon::parse($_POST['start_time']);
            $existingEvent->end_date = Carbon::parse($_POST['end_date']);
            $existingEvent->end_time = Carbon::parse($_POST['end_time']);

            $this->yummyRepository->updateEvent($existingEvent);

            $this->redirectToYummyEvents(true, 'Yummy event updated successfully.');
        } catch (\Exception $e) {
            $this->showYummyEventForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createYummyEvent(): string
    {
        return $this->showYummyEventForm();
    }

    public function createYummyEventPost()
    {
        try {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
                'restaurant_id' => 'required|numeric',
                'total_seats' => 'required|numeric',
                'kids_price' => 'required|numeric',
                'adult_price' => 'required|numeric',
                'reservation_cost' => 'required|numeric',
                'vat' => 'required|numeric',
                'start_time' => 'required',
                'start_date' => 'required',
                'end_time' => 'required',
                'end_date' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->showYummyEventForm('create', $_POST, $validation->errors()->all());
            }

            $eventData = array_intersect_key($_POST, array_flip([
                'restaurant_id',
                'total_seats',
                'kids_price',
                'adult_price',
                'reservation_cost',
                'vat',
                'start_time',
                'start_date',
                'end_time',
                'end_date',
            ]));

            $eventData['vat'] /= 100;

            $newEvent = $this->yummyRepository->createEvent($eventData);

            $this->redirectToYummyEvents(true, 'Yummy event created successfully.');
        } catch (\Exception $e) {
            return $this->showYummyEventForm('create', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    private function getColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'restaurant_name' => ['label' => 'Restaurant Name', 'sortable' => true],
            'total_seats' => ['label' => 'Seats', 'sortable' => true],
            'kids_price' => ['label' => 'Kids Price', 'sortable' => true],
            'adult_price' => ['label' => 'Adult Price', 'sortable' => true],
            'reservation_cost' => ['label' => 'Reservation Fee', 'sortable' => true],
            'vat' => ['label' => 'VAT', 'sortable' => true],
            'start_time' => ['label' => 'Start Time', 'sortable' => true],
            'start_date' => ['label' => 'Start Date', 'sortable' => true],
            'end_time' => ['label' => 'End Time', 'sortable' => true],
            'end_date' => ['label' => 'End Date', 'sortable' => true],
        ];
    }

    private function redirectToYummyEvents(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('events/yummy', $success, $message);
    }

    public function showYummyEventForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'yummy_event',
            $mode,
            $formData,
            $errors,
            $status,
            ['restaurants' => $this->locationRepository->getYummyLocations()]
        );
    }

    public function exportYummyEvents(): void
    {
        $eventsArray = $this->yummyRepository->getSortedEvents('', 'id', 'asc');

        $events = array_map(fn ($row) => (object) $row, $eventsArray);

        $columns = [
            'id' => 'ID',
            'restaurant_name' => 'Restaurant Name',
            'total_seats' => 'Total Seats',
            'kids_price' => 'Kids Price',
            'adult_price' => 'Adult Price',
            'reservation_cost' => 'Reservation Fee',
            'vat' => 'VAT',
            'start_time' => 'Start Time',
            'start_date' => 'Start Date',
            'end_time' => 'End Time',
            'end_date' => 'End Date',
        ];

        $this->exportToCsv('yummy_events', $events, $columns);
    }
}
