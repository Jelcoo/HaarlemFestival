<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Artist;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\EventDance;
use App\Models\EventYummy;
use App\Models\Restaurant;
use App\Models\TicketDance;
use App\Models\TicketYummy;
use App\Models\EventHistory;
use App\Helpers\QueryBuilder;
use App\Models\TicketHistory;
use App\Enum\InvoiceStatusEnum;

class InvoiceRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getInvoices(int $offset = 10, int $limit = 10): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoices = $queryBuilder
            ->table('invoices')
            ->limit($limit, $offset)
            ->get();

        return array_map(fn ($invoice) => new Invoice($invoice), $invoices);
    }

    public function getAllInvoices(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoices = $queryBuilder
            ->table('invoices')
            ->get();

        return array_map(fn ($invoice) => new Invoice($invoice), $invoices);
    }

    public function getInvoicesByUserId(int $userId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoices = $queryBuilder
            ->table('invoices')
            ->where('user_id', '=', $userId)
            ->get();

        foreach ($invoices as &$invoice) {
            $invoice['is_payable'] = $this->isPayableInvoice($invoice['id'], $userId);
        }

        return array_map(fn ($invoice) => new Invoice($invoice), $invoices);
    }

    public function getInvoiceById(int $invoiceId): ?Invoice
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoice = $queryBuilder
            ->table('invoices')
            ->where('id', '=', $invoiceId)
            ->first();

        return $invoice ? new Invoice($invoice) : null;
    }

    public function isPayableInvoice(int $invoiceId, int $userId): bool
    {
        $sql = 'SELECT * FROM invoices WHERE id = :id AND user_id = :user_id AND (status = :later OR status = :failed) AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            'id' => $invoiceId,
            'user_id' => $userId,
            'later' => InvoiceStatusEnum::LATER->value,
            'failed' => InvoiceStatusEnum::FAILED->value,
        ]);

        return $stmt->rowCount() > 0;
    }

    // Pdf

    public function getInvoiceWithAllTickets(int $invoiceId): ?array
    {
        $invoice = $this->getInvoiceById($invoiceId);
        if (!$invoice) {
            return null;
        }
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['id' => $invoice->user_id]);

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return [
            'invoice' => $invoice,
            'user' => new User($user),
            'danceTickets' => $this->getDanceTickets($invoiceId),
            'yummyTickets' => $this->getYummyTickets($invoiceId),
            'historyTickets' => $this->getHistoryTickets($invoiceId),
        ];
    }

    public function getDanceTickets(int $invoiceId): array
    {
        $sql = '
            SELECT t.*, e.*, l.*, a.*
            FROM dance_tickets t
            JOIN dance_events e ON e.id = t.dance_event_id
            JOIN locations l ON l.id = e.location_id
            LEFT JOIN dance_event_artists da ON da.event_id = e.id
            LEFT JOIN artists a ON a.id = da.artist_id
            WHERE t.invoice_id = :invoiceId
        ';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['invoiceId' => $invoiceId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $grouped = [];

        foreach ($rows as $row) {
            $tid = $row['id'];
            if (!isset($grouped[$tid])) {
                $grouped[$tid] = [
                    'ticket' => new TicketDance($row),
                    'event' => new EventDance($row),
                    'location' => new Location($row),
                    'artists' => [],
                ];
            }

            if (!empty($row['name'])) {
                $grouped[$tid]['artists'][] = new Artist($row);
            }
        }

        return array_values($grouped);
    }

    public function getYummyTickets(int $invoiceId): array
    {
        $sql = '
            SELECT t.*, e.*, r.*, l.*
            FROM yummy_tickets t
            JOIN yummy_events e ON e.id = t.yummy_event_id
            JOIN restaurants r ON r.id = e.restaurant_id
            JOIN locations l ON l.id = r.location_id
            WHERE t.invoice_id = :invoiceId
        ';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['invoiceId' => $invoiceId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tickets = [];

        foreach ($rows as $row) {
            $tickets[] = [
                'ticket' => new TicketYummy($row),
                'event' => new EventYummy($row),
                'restaurant' => new Restaurant($row),
                'location' => new Location($row),
            ];
        }

        return $tickets;
    }

    public function getHistoryTickets(int $invoiceId): array
    {
        $sql = '
            SELECT t.*, e.*
            FROM history_tickets t
            JOIN history_events e ON e.id = t.history_event_id
            WHERE t.invoice_id = :invoiceId
        ';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute(['invoiceId' => $invoiceId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tickets = [];

        foreach ($rows as $row) {
            $tickets[] = [
                'ticket' => new TicketHistory($row),
                'event' => new EventHistory($row),
            ];
        }

        return $tickets;
    }
}
