<?php

namespace App\Models\DTO;

use App\Models\Location;
use App\Enum\DanceSessionEnum;
use BaconQrCode\Encoder\QrCode;

class CompleteDanceEvent 
{
    public int $id;
    public array $artists; 
    public Location $location;
    public int $total_tickets;
    public string $session;
    public float $price;
    public float $vat;
    public \DateTime $start_time;
    public \DateTime $start_date;
    public \DateTime $end_time;
    public \DateTime $end_date;
    public string $qrCode;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'] ?? 0; 
        $this->artists = $collection['artists']; 
        $this->location = $collection['location']; 
        $this->total_tickets = $collection['total_tickets'];
        $this->session = $collection['session'];
        $this->price = $collection['price'];
        $this->vat = $collection['vat'];
        $this->start_time = new \DateTime($collection['start_time']);
        $this->start_date = new \DateTime($collection['start_date']);
        $this->end_time = new \DateTime($collection['end_time']);
        $this->end_date = new \DateTime($collection['end_date']);
    }
}
