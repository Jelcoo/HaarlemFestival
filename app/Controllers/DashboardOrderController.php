<?php

namespace App\Controllers;

use App\Repositories\InvoiceRepository;
use App\Repositories\TicketRepository;
use App\Models\Invoice;

class DashboardOrderController extends DashboardController
{
    private TicketRepository $ticketRepository;
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->ticketRepository = new TicketRepository();
        $this->invoiceRepository = new InvoiceRepository();
    }

    public function index(): string
    {
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * 10; 

        $invoices = $this->invoiceRepository->getInvoices($offset);

        return $this->renderPage('orders', [
            'invoices' => $invoices,
            'page' => $page
        ]);
}

}
