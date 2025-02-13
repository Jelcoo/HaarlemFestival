<?php

namespace App\Models;

class EventHistory extends Event
{
    public int $seats_per_tour;
    public string $language;
    public string $guide;
    public float $family_price;
    public float $single_price;

    public function __construct(array $collection)
    {
        parent::__construct($collection);

        $this->seats_per_tour = $collection['seats_per_tour'];
        $this->language = $collection['language'];
        $this->guide = $collection['guide'];
        $this->family_price = $collection['family_price'];
        $this->single_price = $collection['single_price'];
    }
}
