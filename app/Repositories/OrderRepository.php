<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\EventDance;
use App\Models\EventYummy;
use App\Models\EventHistory;
use App\Enum\ItemQuantityEnum;
use App\Enum\InvoiceStatusEnum;

class OrderRepository extends Repository
{
    public function createOrder(Cart $cart)
    {
        $pdoConnection = $this->getConnection();
        try {
            $pdoConnection->beginTransaction();

            $sql = 'INSERT INTO invoices (user_id) VALUES (:user_id)';
            $stmt = $pdoConnection->prepare($sql);
            $stmt->execute(['user_id' => 1]);

            $invoiceId = $pdoConnection->lastInsertId();

            foreach ($cart->items as $item) {
                if ($item->event_model == EventDance::class) {
                    for ($i = 0; $i < $item->quantity(); ++$i) {
                        $sql = 'INSERT INTO dance_tickets (dance_event_id, invoice_id, all_access) VALUES (:dance_event_id, :invoice_id, :all_access)';
                        $stmt = $pdoConnection->prepare($sql);
                        $stmt->execute([
                            'dance_event_id' => $item->event_id,
                            'invoice_id' => $invoiceId,
                            'all_access' => ($item->quantities[0]->type === ItemQuantityEnum::ALL_ACCESS) ? 1 : 0,
                        ]);
                    }
                } elseif ($item->event_model == EventYummy::class) {
                    $sql = 'INSERT INTO yummy_tickets (yummy_event_id, invoice_id, kids_count, adult_count) VALUES (:yummy_event_id, :invoice_id, :kids_count, :adult_count)';
                    $stmt = $pdoConnection->prepare($sql);
                    $stmt->execute([
                        'yummy_event_id' => $item->event_id,
                        'invoice_id' => $invoiceId,
                        'kids_count' => $item->quantities[0]->quantity,
                        'adult_count' => $item->quantities[1]->quantity,
                    ]);
                } elseif ($item->event_model == EventHistory::class) {
                    $sql = 'INSERT INTO history_tickets (history_event_id, invoice_id, total_seats, family_ticket) VALUES (:history_event_id, :invoice_id, :total_seats, :family_ticket)';
                    $stmt = $pdoConnection->prepare($sql);
                    $stmt->execute([
                        'history_event_id' => $item->event_id,
                        'invoice_id' => $invoiceId,
                        'total_seats' => $item->quantities[0]->quantity,
                        'family_ticket' => ($item->quantities[0]->type === ItemQuantityEnum::FAMILY) ? 1 : 0,
                    ]);
                }
            }

            $pdoConnection->commit();

            return intval($invoiceId);
        } catch (\Exception $e) {
            $pdoConnection->rollBack();
            throw $e;
        }
    }

    public function checkDanceTicketAvailable(int $eventId, int $quantity, ItemQuantityEnum $type): bool
    {
        $pdoConnection = $this->getConnection();

        $sql = 'SELECT 
                DE.id,
                DE.total_tickets,
                COUNT(DISTINCT CASE 
                    WHEN i.status = "completed" OR 
                        (i.status = "later" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24) OR
                        (i.status = "failed" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24)
                    THEN DT.id 
                END) AS total_tickets_sold,
                SUM(CASE 
                    WHEN (i.status = "completed" OR 
                        (i.status = "later" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24) OR
                        (i.status = "failed" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24)) 
                        AND DT.all_access = 1 
                    THEN 1 
                    ELSE 0 
                END) AS all_access_sold,
                SUM(CASE 
                    WHEN (i.status = "completed" OR 
                        (i.status = "later" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24) OR
                        (i.status = "failed" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24)) 
                        AND (DT.all_access = 0 OR DT.all_access IS NULL) 
                    THEN 1 
                    ELSE 0 
                END) AS single_sold
                FROM dance_events AS DE
                LEFT JOIN dance_tickets AS DT ON DT.dance_event_id = DE.id
                LEFT JOIN invoices i ON DT.invoice_id = i.id
                WHERE DE.id = :dance_event_id
                GROUP BY DE.id, DE.total_tickets';

        $stmt = $pdoConnection->prepare($sql);
        $stmt->execute([
            'dance_event_id' => $eventId,
        ]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $total = (int) $result['total_tickets'];
        $allAccessLimit = floor($total * 0.10);
        $singleLimit = $total - $allAccessLimit;

        $allAccessSold = (int) $result['all_access_sold'];
        $singleSold = (int) $result['single_sold'];

        if ($type == ItemQuantityEnum::ALL_ACCESS) {
            return ($allAccessSold + $quantity) <= $allAccessLimit;
        } else {
            return ($singleSold + $quantity) <= $singleLimit;
        }
    }

    public function checkYummyTicketAvailable(int $eventId, int $adultQuantity, int $childrenQuantity): bool
    {
        $pdoConnection = $this->getConnection();

        $sql = 'SELECT 
                YE.id, 
                YE.total_seats - COALESCE(
                    SUM(CASE 
                        WHEN i.status = "completed" OR 
                            (i.status = "later" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24) OR
                            (i.status = "failed" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24)
                        THEN YT.kids_count + YT.adult_count 
                        ELSE 0 
                    END), 0) - :adult_quantity - :children_quantity AS seats_remaining 
                FROM yummy_events AS YE
                LEFT JOIN yummy_tickets AS YT ON YT.yummy_event_id = YE.id
                LEFT JOIN invoices i ON YT.invoice_id = i.id
                WHERE YE.id = :yummy_event_id
                GROUP BY YE.id, YE.total_seats';

        $stmt = $pdoConnection->prepare($sql);
        $stmt->execute([
            'yummy_event_id' => $eventId,
            'adult_quantity' => $adultQuantity,
            'children_quantity' => $childrenQuantity,
        ]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['seats_remaining'] >= 0;
    }

    public function checkHistoryTicketAvailable(int $eventId, int $seats): bool
    {
        $pdoConnection = $this->getConnection();

        $sql = 'SELECT 
                HE.id, 
                HE.seats_per_tour - COALESCE(
                    SUM(CASE 
                        WHEN i.status = "completed" OR 
                            (i.status = "later" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24) OR
                            (i.status = "failed" AND TIMESTAMPDIFF(HOUR, i.created_at, NOW()) < 24)
                        THEN HT.total_seats 
                        ELSE 0 
                    END), 0) - :seats AS seats_remaining 
            FROM history_events AS HE
            LEFT JOIN history_tickets AS HT ON HT.history_event_id = HE.id
            LEFT JOIN invoices i ON HT.invoice_id = i.id
            WHERE HE.id = :history_event_id
            GROUP BY HE.id, HE.seats_per_tour';
        $stmt = $pdoConnection->prepare($sql);
        $stmt->execute([
            'history_event_id' => $eventId,
            'seats' => $seats,
        ]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['seats_remaining'] >= 0;
    }

    public function updateOrderStatus(int $orderId, InvoiceStatusEnum $status)
    {
        $pdoConnection = $this->getConnection();

        $sql = 'UPDATE invoices SET status = :status WHERE id = :id';
        $stmt = $pdoConnection->prepare($sql);
        $stmt->execute([
            'status' => $status->value,
            'id' => $orderId,
        ]);
    }

    public function completeOrder(int $orderId)
    {
        $pdoConnection = $this->getConnection();

        $sql = 'UPDATE invoices SET completed_at = NOW() WHERE id = :id';
        $stmt = $pdoConnection->prepare($sql);
        $stmt->execute([
            'id' => $orderId,
        ]);
    }

    public function setStripeId(int $orderId, string $data)
    {
        $pdoConnection = $this->getConnection();

        $sql = 'UPDATE invoices SET stripe_payment_id = :data WHERE id = :id';
        $stmt = $pdoConnection->prepare($sql);
        $stmt->execute([
            'data' => $data,
            'id' => $orderId,
        ]);
    }
}
