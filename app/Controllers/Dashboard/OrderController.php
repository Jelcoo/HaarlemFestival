<?php

namespace App\Controllers\Dashboard;

use App\Repositories\InvoiceRepository;

class OrderController extends DashboardController
{
    private InvoiceRepository $invoiceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceRepository = new InvoiceRepository();
    }

    public function index(): string
    {
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * 10;

        $invoices = $this->invoiceRepository->getInvoices($offset);

        return $this->renderPage('orders', [
            'invoices' => $invoices,
            'page' => $page,
        ]);
    }
}
