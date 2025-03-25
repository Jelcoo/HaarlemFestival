<?php

namespace App\Controllers\Dashboard;

use Rakit\Validation\Validator;
use Carbon\Carbon;
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
            $this->redirectToDanceEvents();
        }

        return $this->renderPage(
            'yummy_events',
            [
                'events' => $this->yummyRepository->getAllEvents(),
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
        if (!$eventId) $this->redirectToYummyEvents(false, 'Invalid yummy event ID.');

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
        if (!$eventId) $this->redirectToYummyEvents(false, 'Invalid yummy event ID.');

        $event = $this->yummyRepository->getEventById($eventId);

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
        ];

        return $this->showYummyEventForm('edit', $formData);
    }

    public function editYummyEventPost(): void
    {
        try {
            $eventId = $_POST['id'] ?? null;
            if (!$eventId) $this->redirectToYummyEvents(false, 'Invalid yummy event ID.');

            $existingEvent = $this->yummyRepository->getEventById($eventId);

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
                throw new \Exception(implode(' ', $validation->errors()->all()));
            }

            $existingEvent->restaurant_id = $_POST['restaurant_id'];
            $existingEvent->total_seats = $_POST['total_seats'];
            $existingEvent->kids_price = $_POST['kids_price'];
            $existingEvent->adult_price = $_POST['adult_price'];
            $existingEvent->reservation_cost = $_POST['reservation_cost'];
            $existingEvent->vat = $_POST['vat'];
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

    public function createYummyEventPost(): string
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
                $errors = $validation->errors();
                return $this->showYummyEventForm('create', $_POST, $errors);
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
            'restaurant_name' => ['label' => 'Restaurant ID', 'sortable' => true],
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
}