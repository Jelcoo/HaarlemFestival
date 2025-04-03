<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repositories\InvoiceRepository;
use App\Application\PageLoader;
use App\Models\Location;
use App\Models\Restaurant;

class InvoiceHelper
{
    private InvoiceRepository $invoiceRepository;
    private PageLoader $pageLoader;

    public function __construct()
    {
        $this->invoiceRepository = new InvoiceRepository();
        $this->pageLoader = new PageLoader();
    }

    private function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function generateInvoicePdf(int $invoiceId): string
    {
        $invoiceData = $this->invoiceRepository->getInvoiceWithAllTickets($invoiceId);

        $totals = [
            'dance' => 0.0,
            'yummy' => 0.0,
            'history' => 0.0,
            'grand' => 0.0,
        ];

        foreach (['danceTickets', 'yummyTickets', 'historyTickets'] as $type) {
            foreach ($invoiceData[$type] as &$entry) {
                $event = $entry['event'];

                if (!isset($entry['price'])) {
                    switch ($type) {
                        case 'danceTickets':
                            $entry['price'] = $event->price * (1 + $event->vat);
                            break;
                        case 'yummyTickets':
                            $entry['price'] = $event->reservation_cost * (1 + $event->vat);
                            break;
                        case 'historyTickets':
                            $price = $entry['ticket']->family_ticket
                                ? $event->family_price
                                : $entry['ticket']->total_seats * $event->single_price;
                            $entry['price'] = $price * (1 + $event->vat);
                            break;
                    }
                }

                $totals[explode('Tickets', $type)[0]] += $entry['price'];

                if ($type === 'yummyTickets') {
                    if (!isset($entry['restaurant'])) {
                        $entry['restaurant'] = new Restaurant([]);
                    }

                    if (!isset($entry['restaurant']->location)) {
                        $entry['restaurant']->location = new Location([
                            'id' => 0,
                            'name' => 'Unknown Location',
                            'event_type' => 'yummy',
                            'coordinates' => null,
                            'address' => null,
                            'preview_description' => null,
                            'main_description' => null,
                        ]);
                    }
                }
            }
        }

        $totals['grand'] = $totals['dance'] + $totals['yummy'] + $totals['history'];

        $html = $this->pageLoader
            ->setLayout('pdf')
            ->setPage('invoices/pdf')
            ->render([...$invoiceData, 'totals' => $totals]);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $this->ensureDirectoryExists(__DIR__ . '/../../storage/invoices');
        $path = __DIR__ . "/../../storage/invoices/invoice_{$invoiceId}.pdf";
        file_put_contents($path, $dompdf->output());

        return $path;
    }

    public function generateAllTicketsForInvoice(int $invoiceId): array
    {
        $invoiceData = $this->invoiceRepository->getInvoiceWithAllTickets($invoiceId);

        $ticketPaths = [];
        foreach (['danceTickets', 'yummyTickets', 'historyTickets'] as $type) {
            foreach ($invoiceData[$type] ?? [] as $entry) {
                $ticketPaths[] = $this->generateSingleTicketPdf($type, $entry);
            }
        }

        return $ticketPaths;
    }

    private function generateSingleTicketPdf(string $type, array $entry): string
    {
        $qrCodePath = '/img/qrcode.png';

        if ($type === 'yummyTickets') {
            if (!isset($entry['restaurant'])) {
                $entry['restaurant'] = new Restaurant([]);
            }

            if (!isset($entry['restaurant']->location)) {
                $entry['restaurant']->location = new Location([
                    'id' => 0,
                    'name' => 'Unknown Location',
                    'event_type' => 'yummy',
                    'coordinates' => null,
                    'address' => null,
                    'preview_description' => null,
                    'main_description' => null,
                ]);
            }
        }

        $html = $this->pageLoader
            ->setLayout('pdf')
            ->setPage("tickets/pdf_{$type}.php")
            ->render([
                'entry' => $entry,
                'qrPath' => $qrCodePath
            ]);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $this->ensureDirectoryExists(__DIR__ . '/../../storage/tickets');

        $ticketId = $entry['ticket']->id;
        $path = __DIR__ . "/../../storage/tickets/{$type}_ticket_{$ticketId}.pdf";
        file_put_contents($path, $dompdf->output());

        return $path;
    }
}
