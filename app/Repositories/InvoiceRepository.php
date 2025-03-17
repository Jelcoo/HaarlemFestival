<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Helpers\QueryBuilder;

class InvoiceRepository extends Repository
{
    public function getInvoices(int $offset = 10, int $limit = 10): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoices = $queryBuilder
            ->table('invoices')
            ->limit($limit, $offset)
            ->get();

        return array_map(fn ($invoice) => new Invoice($invoice), $invoices);
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoice = $queryBuilder
            ->table('invoices')
            ->where('id', '=', $id)
            ->first();

        return $invoice ? new Invoice($invoice) : null;
    }

    public function getInvoicesByUserId(int $userId): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $invoices = $queryBuilder
            ->table('invoices')
            ->where('user_id', '=', $userId)
            ->get();

        return array_map(fn ($invoice) => new Invoice($invoice), $invoices);
    }
}
