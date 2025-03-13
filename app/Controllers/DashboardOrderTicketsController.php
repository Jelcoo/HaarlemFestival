<?php

namespace App\Controllers;

use App\Repositories\TicketRepository;
use App\Repositories\InvoiceRepository;

class DashboardOrderTicketsController extends DashboardController
{
    private TicketRepository $ticketRepository;
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ticketRepository = new TicketRepository();
    }

    public function index(): string
    {
        $invoiceId = $_GET['invoice_id'] ?? null;
        $danceTickets = $this->ticketRepository->getDanceTickets($invoiceId);
        $historyTickets = $this->ticketRepository->getHistoryTickets($invoiceId);
        $yummyTickets = $this->ticketRepository->getYummyTickets($invoiceId);
        invoiceId:

        return $this->renderPage('order_tickets', ['danceTickets' => $danceTickets, 'historyTickets' => $historyTickets, 'yummyTickets' => $yummyTickets]);
    }
}
