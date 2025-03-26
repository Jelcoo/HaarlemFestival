<?php

namespace App\Controllers;

use App\Helpers\QrCodeGenerator;
use App\Repositories\TicketRepository;
use App\Repositories\InvoiceRepository;

class ProgramController extends Controller
{
    private TicketRepository $ticketRepository;
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceRepository = new InvoiceRepository();
        $this->ticketRepository = new TicketRepository();
    }

    public function index(): string
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $invoices = $this->invoiceRepository->getInvoicesByUserId($user_id);

        return $this->pageLoader->setPage('order')->render(['invoices' => $invoices]);
    }
    public function program(): string
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $invoices = $this->invoiceRepository->getInvoicesByUserId($user_id);
        
        $allDanceEvents = [];
        $allHistoryEvents = [];
        $allYummyEvents = [];

        foreach ($invoices as $invoice) {
            $allDanceEvents = array_merge($allDanceEvents, $this->getCompleteDanceEvents($invoice->id));
            $allHistoryEvents = array_merge($allHistoryEvents, $this->getCompleteHistoryEvents($invoice->id));
            $allYummyEvents = array_merge($allYummyEvents, $this->getCompleteRestaurantEvents($invoice->id));
        }

        $cleanDanceEvents = [];

        foreach ($allDanceEvents as $event) {
            if (isset($event['ticket']) && $event['ticket']->all_access == 'false') {
                $cleanDanceEvents[] = $event;
            }
        }

        return $this->pageLoader->setPage('program')->render([
            'completeDanceEvents' => $cleanDanceEvents,
            'completeHistoryEvents' => $allHistoryEvents,
            'completeRestaurantEvents' => $allYummyEvents,
        ]);
    }

    public function tickets(): string
    {
        $invoiceId = $_GET['invoice_id'] ?? null;

        $historyTickets = $this->ticketRepository->getHistoryTickets($invoiceId);
        $yummyTickets = $this->ticketRepository->getYummyTickets($invoiceId);
        $danceTickets = $this->ticketRepository->getDanceTickets($invoiceId);

        $completeDanceEvents = [];
        foreach ($danceTickets as $danceTicket) {
            if (isset($danceTicket)) {
                $danceEvent = $this->ticketRepository->getCompleteDanceEvent($danceTicket->dance_event_id);
                $qrCodeGenerator = new QrCodeGenerator();
                $danceTicket->qrcode = $qrCodeGenerator->generateQRCode($danceTicket->qrcode,'dance');
                $completeDanceEvents[] = [
                    'ticket' => $danceTicket,
                    'event' => $danceEvent,
                ];
            }
        $completeHistoryEvents = [];
        foreach ($historyTickets as $historyTicket) {
            if (isset($historyTicket)) {
                $completeHistoryEvent = $this->ticketRepository->getEventHistoryByEventId($historyTicket->history_event_id);
                $qrCodeGenerator = new QrCodeGenerator();
                $historyTicket->qrcode = $qrCodeGenerator->generateQRCode($historyTicket->qrcode,'history');
                $completeHistoryEvents[] = [
                    'ticket' => $historyTicket,
                    'event' => $completeHistoryEvent,
                ];
            }
        }

        $completeRestaurantEvents = [];
        foreach ($yummyTickets as $yummyTicket) {
            if (isset($yummyTicket)) {
                $completeRestaurantEvent = $this->ticketRepository->getRestaurantInformation($yummyTicket->yummy_event_id);

                $qrCodeGenerator = new QrCodeGenerator();
                $yummyTicket->qrcode = $qrCodeGenerator->generateQRCode($yummyTicket->qrcode,'yummy');
                $completeRestaurantEvents[] = [
                    'ticket' => $yummyTicket,
                    'event' => $completeRestaurantEvent,
                ];
            }
        }

            return $this->pageLoader->setPage('tickets')->render([
                'completeDanceEvents' => $completeDanceEvents,
                'completeHistoryEvents' => $completeHistoryEvents,
                'completeRestaurantEvents' => $completeRestaurantEvents]);
        }

        return $this->pageLoader->setPage('order')->render();
    }

    public function qrcode(): string
    {
        $user_id = $_SESSION['user_id'];
        $invoices = $this->invoiceRepository->getInvoicesByUserId($user_id);

        return $this->pageLoader->setPage('order')->render(['invoices' => $invoices]);
    }
    private function getCompleteDanceEvents($invoiceId)
    {
        $danceTickets = $this->ticketRepository->getDanceTickets($invoiceId);
        $completeDanceEvents = [];

        foreach ($danceTickets as $danceTicket) {
            if (isset($danceTicket)) {
                $danceEvent = $this->ticketRepository->getCompleteDanceEvent($danceTicket->dance_event_id);
                $qrCodeGenerator = new QrCodeGenerator();
                $danceTicket->qrcode = $qrCodeGenerator->generateQRCode($danceTicket->qrcode, 'dance');
                $completeDanceEvents[] = ['ticket' => $danceTicket, 'event' => $danceEvent];
            }
        }

        return $completeDanceEvents;
    }

    private function getCompleteHistoryEvents($invoiceId)
    {
        $historyTickets = $this->ticketRepository->getHistoryTickets($invoiceId);
        $completeHistoryEvents = [];

        foreach ($historyTickets as $historyTicket) {
            if (isset($historyTicket)) {
                $completeHistoryEvent = $this->ticketRepository->getEventHistoryByEventId($historyTicket->history_event_id);
                $qrCodeGenerator = new QrCodeGenerator();
                $historyTicket->qrcode = $qrCodeGenerator->generateQRCode($historyTicket->qrcode, 'history');
                $completeHistoryEvents[] = ['ticket' => $historyTicket, 'event' => $completeHistoryEvent];
            }
        }

        return $completeHistoryEvents;
    }

    private function getCompleteRestaurantEvents($invoiceId)
    {
        $yummyTickets = $this->ticketRepository->getYummyTickets($invoiceId);
        $completeRestaurantEvents = [];

        foreach ($yummyTickets as $yummyTicket) {
            if (isset($yummyTicket)) {
                $completeRestaurantEvent = $this->ticketRepository->getRestaurantInformation($yummyTicket->yummy_event_id);
                $qrCodeGenerator = new QrCodeGenerator();
                $yummyTicket->qrcode = $qrCodeGenerator->generateQRCode($yummyTicket->qrcode, 'yummy');
                $completeRestaurantEvents[] = ['ticket' => $yummyTicket, 'event' => $completeRestaurantEvent];
            }
        }

        return $completeRestaurantEvents;
    }
}
