<?php

namespace App\Repositories;

class OrderRepository extends Repository
{
    public function createOrder(array $data)
    {
        try {
            $this->pdoConnection->beginTransaction();

            $sql = 'INSERT INTO invoices (user_id) VALUES (:user_id)';
            $stmt = $this->pdoConnection->prepare($sql);
            $stmt->execute(['user_id' => 1]);

            $invoiceId = $this->pdoConnection->lastInsertId();

            foreach ($data['dance'] as $dance) {
                for ($i = 0; $i < $dance['quantity']; ++$i) {
                    $sql = 'INSERT INTO dance_tickets (dance_event_id, invoice_id, all_access) VALUES (:dance_event_id, :invoice_id, :all_access)';
                    $stmt = $this->pdoConnection->prepare($sql);
                    $stmt->execute([
                        'dance_event_id' => $dance['event_id'],
                        'invoice_id' => $invoiceId,
                        'all_access' => ($dance['all_access'] == 1) ? 1 : 0,
                    ]);
                }
            }

            foreach ($data['yummy'] as $yummy) {
                $sql = 'INSERT INTO yummy_tickets (yummy_event_id, invoice_id, kids_count, adult_count) VALUES (:yummy_event_id, :invoice_id, :kids_count, :adult_count)';
                $stmt = $this->pdoConnection->prepare($sql);
                $stmt->execute([
                    'yummy_event_id' => $yummy['event_id'],
                    'invoice_id' => $invoiceId,
                    'kids_count' => $yummy['children_quantity'],
                    'adult_count' => $yummy['adult_quantity'],
                ]);
            }

            foreach ($data['history'] as $history) {
                $sql = 'INSERT INTO history_tickets (history_event_id, invoice_id, total_seats, family_ticket) VALUES (:history_event_id, :invoice_id, :total_seats, :family_ticket)';
                $stmt = $this->pdoConnection->prepare($sql);
                $stmt->execute([
                    'history_event_id' => $history['event_id'][0],
                    'invoice_id' => $invoiceId,
                    'total_seats' => $history['seats'],
                    'family_ticket' => ($history['type'] == 'family') ? 1 : 0,
                ]);
            }

            $this->pdoConnection->commit();

            return intval($invoiceId);
        } catch (\Exception $e) {
            $this->pdoConnection->rollBack();
            throw $e;
        }
    }

    public function checkAvailability(array $data)
    {
        $unavailable = [];
        foreach ($data['dance'] as $dance) {
            // Updated query to get detailed ticket breakdown
            $sql = '
                SELECT 
                    DE.id,
                    DE.total_tickets,
                    COUNT(DT.id) AS total_tickets_sold,
                    SUM(CASE WHEN DT.all_access = 1 THEN 1 ELSE 0 END) AS all_access_sold,
                    SUM(CASE WHEN DT.all_access = 0 OR DT.all_access IS NULL THEN 1 ELSE 0 END) AS single_sold
                FROM dance_events AS DE
                LEFT JOIN dance_tickets AS DT ON DT.dance_event_id = DE.id
                WHERE DE.id = :dance_event_id
                GROUP BY DE.id, DE.total_tickets
            ';

            $stmt = $this->pdoConnection->prepare($sql);
            $stmt->execute([
                'dance_event_id' => $dance['event_id'],
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                $unavailable[] = ['dance' => $dance['event_id'], 'reason' => 'Event not found'];
                continue;
            }

            // Calculate ticket type limits
            $total = (int) $result['total_tickets'];
            $quantity = (int) $dance['quantity'];
            $allAccessLimit = floor($total * 0.10);
            $singleLimit = $total - $allAccessLimit;

            $allAccessSold = (int) $result['all_access_sold'];
            $singleSold = (int) $result['single_sold'];

            if (!empty($dance['all_access']) && $dance['all_access'] == 1) {
                // User is trying to buy an all-access ticket
                if (($allAccessSold + $quantity) > $allAccessLimit) {
                    $unavailable[] = [
                        'dance' => $result['id'],
                        'reason' => 'Not enough all-access tickets available',
                    ];
                }
            } else {
                // User is trying to buy single tickets
                if (($singleSold + $quantity) > $singleLimit) {
                    $unavailable[] = [
                        'dance' => $result['id'],
                        'reason' => 'Not enough single tickets available',
                    ];
                }
            }
        }

        foreach ($data['yummy'] as $yummy) {
            $sql = 'SELECT YE.id, YE.total_seats - COALESCE(SUM(YT.kids_count + YT.adult_count), 0) - :adult_quantity - :children_quantity AS seats_remaining 
                    FROM yummy_events AS YE
                    LEFT JOIN yummy_tickets AS YT ON YT.yummy_event_id = YE.id
                    WHERE YE.id = :yummy_event_id
                    GROUP BY YE.id, YE.total_seats';
            $stmt = $this->pdoConnection->prepare($sql);
            $stmt->execute([
                'yummy_event_id' => $yummy['event_id'],
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result['tickets_remaining'] < 0) {
                $unavailable[] = [
                    'yummy' => $result['id'],
                    'reason' => 'Not enough seats available',
                ];
            }
        }

        foreach ($data['history'] as $history) {
            foreach ($history['event_id'] as $id) {
                $sql = 'SELECT HE.id, HE.seats_per_tour - COALESCE(SUM(HT.total_seats), 0) - :seats AS seats_remaining 
                        FROM history_events AS HE
                        LEFT JOIN history_tickets AS HT ON HT.history_event_id = HE.id
                        WHERE HE.id = :history_event_id
                        GROUP BY HE.id, HE.seats_per_tour';
                $stmt = $this->pdoConnection->prepare($sql);
                $stmt->execute([
                    'history_event_id' => $id,
                    'seats' => $history['seats'],
                ]);
                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($result['seats_remaining'] < 0) {
                    $unavailable[] = [
                        'history' => $result['id'],
                        'reason' => 'Not enough seats available',
                    ];
                }
            }
        }

        return $unavailable;
    }

    public function updateOrderStatus(int $orderId, string $data)
    {
        $sql = 'UPDATE invoices SET status = :data WHERE id = :id';
        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->execute([
            'data' => $data,
            'id' => $orderId,
        ]);
    }

    public function completeOrder(int $orderId)
    {
        $sql = 'UPDATE invoices SET completed_at = NOW() WHERE id = :id';
        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->execute([
            'id' => $orderId,
        ]);
    }

    public function setStripeId(int $orderId, string $data)
    {
        $sql = 'UPDATE invoices SET stripe_payment_id = :data WHERE id = :id';
        $stmt = $this->pdoConnection->prepare($sql);
        $stmt->execute([
            'data' => $data,
            'id' => $orderId,
        ]);
    }
}
