<?php

namespace App\Repositories;

use App\Models\TicketDance;
use App\Models\TicketYummy;
use App\Models\TicketHistory;

use App\Helpers\QueryBuilder;

class TicketRepository extends Repository
{
    /*
    public function getDanceTickets(int $offset = 0): array
{
    $limit = 10;

    $queryBuilder = new QueryBuilder($this->getConnection());

    $danceTickets = $queryBuilder->table('dance_tickets')->limit($limit, $offset)->get();

    return array_map(fn($danceTicket) => new TicketDance($danceTicket), $danceTickets);
}

    
public function getYummyTickets(int $offset = 0): array
{
    $limit = 10;

    $queryBuilder = new QueryBuilder($this->getConnection());

    $yummyTickets = $queryBuilder->table('yummy_tickets')->limit($limit, $offset)->get();

    return array_map(fn($yummyTicket) => new TicketYummy($yummyTicket), $yummyTickets);
}

public function getHistoryTickets(int $offset = 0): array
{
    $limit = 10;

    $queryBuilder = new QueryBuilder($this->getConnection());

    $historyTickets = $queryBuilder->table('history_tickets')->limit($limit, $offset)->get();

    return array_map(fn($historyTicket) => new TicketHistory($historyTicket), $historyTickets);
}
*/
public function getDanceTickets(int $invoiceId): array
{
    $queryBuilder = new QueryBuilder($this->getConnection());

    $danceTickets = $queryBuilder
        ->table('dance_tickets')
        ->where('invoice_id', '=', $invoiceId)
        ->get();

    return array_map(fn($danceTicket) => new TicketDance($danceTicket), $danceTickets);
}

public function getYummyTickets(int $invoiceId): array
{
    $queryBuilder = new QueryBuilder($this->getConnection());

    $yummyTickets = $queryBuilder
        ->table('yummy_tickets')
        ->where('invoice_id', '=', $invoiceId)
        ->get();

    return array_map(fn($yummyTicket) => new TicketYummy($yummyTicket), $yummyTickets);
}

public function getHistoryTickets(int $invoiceId): array
{
    $queryBuilder = new QueryBuilder($this->getConnection());

    $historyTickets = $queryBuilder
        ->table('history_tickets')
        ->where('invoice_id', '=', $invoiceId)
        ->get();

    return array_map(fn($historyTicket) => new TicketHistory($historyTicket), $historyTickets);
}


    
}
