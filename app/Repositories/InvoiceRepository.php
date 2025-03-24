<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Helpers\QueryBuilder;
use App\Enum\InvoiceStatusEnum;

class InvoiceRepository extends Repository
{
    public function getInvoices(int $offset = 10, int $limit = 10): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoices = $queryBuilder
            ->table('invoices')
            ->limit($limit, $offset)
            ->get();

        return array_map(fn($invoice) => new Invoice($invoice), $invoices);
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

        return array_map(fn($invoice) => new Invoice($invoice), $invoices);
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
}
