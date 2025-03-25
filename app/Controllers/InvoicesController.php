<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Restaurant;
use App\Repositories\InvoiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\TicketRepository;
use App\Repositories\DanceRepository;
use App\Repositories\YummyRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\ArtistRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\LocationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicesController extends Controller
{
    private InvoiceRepository $invoiceRepository;
    private UserRepository $userRepository;
    private TicketRepository $ticketRepository;
    private YummyRepository $yummyRepository;
    private DanceRepository $danceRepository;
    private HistoryRepository $historyRepository;
    private ArtistRepository $artistRepository;
    private RestaurantRepository $restaurantRepository;
    private LocationRepository $locationRepository;
    

    public function __construct()
    {
        parent::__construct();

        $this->invoiceRepository = new InvoiceRepository();
        $this->userRepository = new UserRepository();
        $this->ticketRepository = new TicketRepository();
        $this->yummyRepository = new YummyRepository();
        $this->danceRepository = new DanceRepository();
        $this->historyRepository = new HistoryRepository();
        $this->artistRepository = new ArtistRepository();
        $this->restaurantRepository = new RestaurantRepository();
        $this->locationRepository = new LocationRepository();

    }

    public function index(): string
    {
        $invoices = $this->invoiceRepository->getAllInvoices();

        return $this->pageLoader
            ->setPage('invoices/index')
            ->render([
                'invoices' => $invoices,
            ]);
    }

    public function generatePdf(): void
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo 'Missing invoice ID.';
            return;
        }
    
        $invoiceId = (int) $_GET['id'];
        $invoice = $this->invoiceRepository->getInvoiceById($invoiceId);
    
        if (!$invoice) {
            http_response_code(404);
            echo 'Invoice not found.';
            return;
        }
    
        $user = $this->userRepository->getUserById($invoice->user_id);
    
        // Dance tickets
        $rawDanceTickets = $this->ticketRepository->getDanceTickets($invoiceId);
        $danceTickets = [];
        $danceTotal = 0.0;
        foreach ($rawDanceTickets as $ticket) {
            $event = $this->danceRepository->getEventById($ticket->dance_event_id);
            $danceTickets[] = [
                'ticket' => $ticket,
                'event' => $event,
                'artists' => $this->artistRepository->getArtistsByEventId($event->id) ?? [],
                'location' => $this->locationRepository->getLocationById($event->location_id),
                'session' => $event->session,
                'price' => $event->price * (1 + $event->vat),
            ];
            $danceTotal += $event->price * (1 + $event->vat);
        }
    
        // Yummy tickets
        $rawYummyTickets = $this->ticketRepository->getYummyTickets($invoiceId);
        $yummyTickets = [];
        $yummyTotal = 0.0;
        foreach ($rawYummyTickets as $ticket) {
            $event = $this->yummyRepository->getEventById($ticket->yummy_event_id);
            $ticketPrice = ($event->reservation_cost * ($ticket->kids_count + $ticket->adult_count)) * (1 + $event->vat);
            $yummyTickets[] = [
                'ticket' => $ticket,
                'event' => $event,
                'restaurant' => $this->restaurantRepository->getRestaurantByIdWithLocation($event->restaurant_id),
                'price' => $ticketPrice,
            ];
            $yummyTotal += $ticketPrice;
        }
    
        // History tickets
        $rawHistoryTickets = $this->ticketRepository->getHistoryTickets($invoiceId);
        $historyTickets = [];
        $historyTotal = 0.0;
        foreach ($rawHistoryTickets as $ticket) {
            $event = $this->historyRepository->getEventById($ticket->history_event_id);
            $price = $ticket->family_ticket
                ? $event->family_price
                : $ticket->total_seats * $event->single_price;
            $historyTickets[] = [
                'ticket' => $ticket,
                'event' => $event,
                'price' => $price,
            ];
            $historyTotal += $price;
        }
    
        $totals = [
            'dance' => $danceTotal,
            'yummy' => $yummyTotal,
            'history' => $historyTotal,
            'grand' => $danceTotal + $yummyTotal + $historyTotal,
        ];
    
        // Render HTML
        $html = $this->pageLoader
            ->setLayout('pdf')
            ->setPage('invoices/pdf')
            ->render([
                'invoice' => $invoice,
                'user' => $user,
                'danceTickets' => $danceTickets,
                'yummyTickets' => $yummyTickets,
                'historyTickets' => $historyTickets,
                'totals' => $totals,
            ]);
    
        // Generate PDF
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Helvetica');
    
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $dompdf->stream("invoice_{$invoiceId}.pdf");
    }
    
}
