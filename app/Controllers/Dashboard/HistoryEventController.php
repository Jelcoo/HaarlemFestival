<?php

namespace App\Controllers\Dashboard;

use Carbon\Carbon;
use Rakit\Validation\Validator;
use App\Repositories\HistoryRepository;
use App\Repositories\LocationRepository;

class HistoryEventController extends DashboardController
{
    private LocationRepository $locationRepository;
    private HistoryRepository $historyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->locationRepository = new LocationRepository();
        $this->historyRepository = new HistoryRepository();
    }

    public function index(): string
    {
        $sortColumn = $_GET['sort'] ?? 'id';
        $sortDirection = $_GET['direction'] ?? 'asc';
        $searchQuery = $_GET['search'] ?? '';

        if (isset($_GET['search']) && $searchQuery === '') {
            $this->redirectToHistoryEvents();
        }

        return $this->renderPage(
            'history_events',
            [
                'events' => $this->historyRepository->getSortedEvents($searchQuery, $sortColumn, $sortDirection),
                'status' => $this->getStatus(),
                'columns' => $this->getColumns(),
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
                'searchQuery' => $searchQuery,
            ]
        );
    }

    public function deleteHistoryEvent(): void
    {
        $eventId = $_POST['id'] ?? null;
        if (!$eventId) {
            $this->redirectToHistoryEvents(false, 'Invalid history event ID.');
        }

        try {
            $success = (bool) $this->historyRepository->deleteEvent($eventId);
            $this->redirectToHistoryEvents($success, $success ? 'History event deleted successfully.' : 'Failed to delete history event.');
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') { // SQLSTATE[23000] => integrity constraint violation
                $this->redirectToHistoryEvents(false, 'Cannot delete this event because it has tickets connected.');
            } else {
                $this->redirectToHistoryEvents(false, 'Database error: ' . $e->getMessage());
            }
        }
    }

    public function editHistoryEvent(): string
    {
        $eventId = $_GET['id'] ?? null;
        if (!$eventId) {
            $this->redirectToHistoryEvents(false, 'Invalid history event ID.');
        }

        $event = $this->historyRepository->getEventById($eventId);
        if (!$event) {
            $this->redirectToHistoryEvents(false, 'History event not found.');
        }

        $formData = [
            'id' => $event->id,
            'language' => $event->language,
            'guide' => $event->guide,
            'seats_per_tour' => $event->seats_per_tour,
            'family_price' => $event->family_price,
            'single_price' => $event->single_price,
            'vat' => $event->vat,
            'start_location' => $event->start_location,
            'start_time' => Carbon::parse($event->start_time)->format('H:i'),
            'start_date' => Carbon::parse($event->start_date)->format('Y-m-d'),
            'end_time' => Carbon::parse($event->end_time)->format('H:i'),
            'end_date' => Carbon::parse($event->end_date)->format('Y-m-d'),
            'has_tickets' => $this->historyRepository->eventHasTickets($eventId),
        ];

        return $this->showHistoryEventForm('edit', $formData);
    }

    public function editHistoryEventPost(): void
    {
        try {
            $eventId = $_POST['id'] ?? null;
            if (!$eventId) {
                $this->redirectToHistoryEvents(false, 'Invalid history event ID.');
            }

            $existingEvent = $this->historyRepository->getEventById($eventId);
            if (!$existingEvent) {
                $this->redirectToHistoryEvents(false, 'History event not found.');
            }

            $validationRules = [
                'language' => 'required',
                'guide' => 'required',
                'start_location' => 'required',
                'start_time' => 'required',
                'start_date' => 'required',
                'end_time' => 'required',
                'end_date' => 'required',
            ];

            if (!isset($_POST['has_tickets']) || !$_POST['has_tickets']) {
                $validationRules['seats_per_tour'] = 'required|numeric';
                $validationRules['family_price'] = 'required|numeric';
                $validationRules['single_price'] = 'required|numeric';
                $validationRules['vat'] = 'required|numeric';
            }

            $validator = new Validator();
            $validation = $validator->validate($_POST, $validationRules);

            $validation = $validator->make($_POST, [

            ]);

            if ($validation->fails()) {
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $existingEvent->language = $_POST['language'];
            $existingEvent->guide = $_POST['guide'];
            $existingEvent->seats_per_tour = $_POST['seats_per_tour'];
            $existingEvent->family_price = $_POST['family_price'];
            $existingEvent->single_price = $_POST['single_price'];
            $existingEvent->vat = $_POST['vat'] / 100;
            $existingEvent->start_location = $_POST['start_location'];
            $existingEvent->start_time = Carbon::parse($_POST['start_time']);
            $existingEvent->start_date = Carbon::parse($_POST['start_date']);
            $existingEvent->end_time = Carbon::parse($_POST['end_time']);
            $existingEvent->end_date = Carbon::parse($_POST['end_date']);

            $this->historyRepository->updateEvent($existingEvent);

            $this->redirectToHistoryEvents(true, 'History event updated successfully.');
        } catch (\Exception $e) {
            $this->showHistoryEventForm('edit', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    public function createHistoryEvent(): string
    {
        return $this->showHistoryEventForm();
    }

    public function createHistoryEventPost()
    {
        try {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
                'language' => 'required',
                'guide' => 'required',
                'seats_per_tour' => 'required|numeric',
                'family_price' => 'required|numeric',
                'single_price' => 'required|numeric',
                'vat' => 'required|numeric',
                'start_location' => 'required',
                'start_time' => 'required',
                'start_date' => 'required',
                'end_time' => 'required',
                'end_date' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->showHistoryEventForm('create', $_POST, $validation->errors()->all());
            }

            $eventData = array_intersect_key($_POST, array_flip([
                'language',
                'guide',
                'seats_per_tour',
                'family_price',
                'single_price',
                'vat',
                'start_location',
                'start_time',
                'start_date',
                'end_time',
                'end_date',
            ]));

            $eventData['vat'] /= 100;

            $newEvent = $this->historyRepository->createEvent($eventData);

            $this->redirectToHistoryEvents(true, 'History event created successfully.');
        } catch (\Exception $e) {
            return $this->showHistoryEventForm('create', $_POST, ['Error: ' . $e->getMessage()]);
        }
    }

    private function getColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'sortable' => true],
            'language' => ['label' => 'Language', 'sortable' => true],
            'guide' => ['label' => 'Guide', 'sortable' => true],
            'seats_per_tour' => ['label' => 'Seats', 'sortable' => true],
            'family_price' => ['label' => 'Family Price', 'sortable' => true],
            'single_price' => ['label' => 'Single Price', 'sortable' => true],
            'vat' => ['label' => 'VAT', 'sortable' => true],
            'start_location' => ['label' => 'Start Location', 'sortable' => true],
            'start_time' => ['label' => 'Start Time', 'sortable' => true],
            'start_date' => ['label' => 'Start Date', 'sortable' => true],
            'end_time' => ['label' => 'End Time', 'sortable' => true],
            'end_date' => ['label' => 'End Date', 'sortable' => true],
        ];
    }

    private function redirectToHistoryEvents(bool $success = false, string $message = ''): void
    {
        $this->redirectTo('events/history', $success, $message);
    }

    public function showHistoryEventForm(string $mode = 'create', array $formData = [], array $errors = [], array $status = []): string
    {
        return $this->showForm(
            'history_event',
            $mode,
            $formData,
            $errors,
            $status,
            ['locations' => $this->locationRepository->getAllLocations()]
        );
    }

    public function exportHistoryEvents(): void
    {
        $eventsArray = $this->historyRepository->getSortedEvents('', 'id', 'asc');

        $events = array_map(fn ($row) => (object) $row, $eventsArray);

        $columns = [
            'id' => 'ID',
            'language' => 'Language',
            'guide' => 'Guide',
            'seats_per_tour' => 'Seats per Tour',
            'single_price' => 'Single Price',
            'family_price' => 'Family Price',
            'vat' => 'VAT',
            'start_location' => 'Start Location',
            'start_time' => 'Start Time',
            'start_date' => 'Start Date',
            'end_time' => 'End Time',
            'end_date' => 'End Date',
        ];

        $this->exportToCsv('history_events', $events, $columns);
    }
}
