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
                        'all_access' => isset($dance['all_access']) ? 1 : 0,
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
                    'history_event_id' => $history['event_id'],
                    'invoice_id' => $invoiceId,
                    'total_seats' => $history['seats'],
                    'family_ticket' => ($history['type'] == 'family') ? 1 : 0,
                ]);
            }

            $this->pdoConnection->commit();
        } catch (\Exception $e) {
            $this->pdoConnection->rollBack();
            throw $e;
        }
    }
}
