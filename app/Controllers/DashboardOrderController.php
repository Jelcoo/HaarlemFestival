<?php
namespace App\Controllers;

use App\Repositories\InvoiceRepository;
use App\Repositories\TicketRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DashboardOrderController extends DashboardController
{
    private InvoiceRepository $invoiceRepository;
    private TicketRepository $ticketRepository;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceRepository = new InvoiceRepository();
        $this->ticketRepository = new TicketRepository();
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

    public function tickets()
    {
        $invoiceId = $_GET['invoice_id'] ?? null;
        $danceTickets = $this->ticketRepository->getDanceTickets($invoiceId);
        $historyTickets = $this->ticketRepository->getHistoryTickets($invoiceId);
        $yummyTickets = $this->ticketRepository->getYummyTickets($invoiceId);

        return $this->renderPage('order_tickets', [
            'danceTickets' => $danceTickets,
            'historyTickets' => $historyTickets,
            'yummyTickets' => $yummyTickets
        ]);
    }

    public function excel()
    {
        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr, true);
        $invoiceId = $jsonObj['invoiceId'] ?? null;

        if (!$invoiceId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invoice ID missing']);
            return;
        }

        $danceTickets = $this->ticketRepository->getDanceTickets($invoiceId);
        $historyTickets = $this->ticketRepository->getHistoryTickets($invoiceId);
        $yummyTickets = $this->ticketRepository->getYummyTickets($invoiceId);

        // Generate and download Excel
        $this->createExcel($danceTickets, $historyTickets, $yummyTickets);
        exit;
    }

    private function createExcel($danceTickets, $historyTickets, $yummyTickets)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dance Tickets
        $row = 1;
        $sheet->setCellValue("A$row", "Dance Tickets");
        $row++;
        foreach ($danceTickets as $ticket) {
            $sheet->setCellValue("A$row", "Ticket ID: {$ticket->id}");
            $sheet->setCellValue("B$row", "Event ID: {$ticket->dance_event_id}");
            $sheet->setCellValue("C$row", "All Access: {$ticket->all_access}");
            $sheet->setCellValue("D$row", "Used: " . ($ticket->ticket_used ? 'Yes' : 'No'));
            $row++;
        }

        // History Tickets
        $row++; 
        $sheet->setCellValue("A$row", "History Tickets");
        $row++;
        foreach ($historyTickets as $ticket) {
            $sheet->setCellValue("A$row", "Ticket ID: {$ticket->id}");
            $sheet->setCellValue("B$row", "Event ID: {$ticket->history_event_id}");
            $sheet->setCellValue("C$row", "Total Seats: {$ticket->total_seats}");
            $sheet->setCellValue("D$row", "Family Ticket: " . ($ticket->family_ticket ? 'Yes' : 'No'));
            $sheet->setCellValue("E$row", "Used: " . ($ticket->ticket_used ? 'Yes' : 'No'));
            $row++;
        }

        // Yummy Tickets
        $row++; 
        $sheet->setCellValue("A$row", "Yummy Tickets");
        $row++;
        foreach ($yummyTickets as $ticket) {
            $sheet->setCellValue("A$row", "Ticket ID: {$ticket->id}");
            $sheet->setCellValue("B$row", "Event ID: {$ticket->yummy_event_id}");
            $sheet->setCellValue("C$row", "Kids: {$ticket->kids_count}");
            $sheet->setCellValue("D$row", "Adults: {$ticket->adult_count}");
            $sheet->setCellValue("E$row", "Used: " . ($ticket->ticket_used ? 'Yes' : 'No'));
            $row++;
        }

        // Headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"tickets_invoice.xlsx\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');  // Sends file to output
    }
}
