<?php

namespace App\Models;

class EventHistory extends Event
{
    public int $seats_per_tour;
    public string $language;
    public string $guide;
    public float $family_price;
    public float $single_price;
    public string $start_location;

    public function __construct()
    {
        $arguments = func_get_args();

        if (!empty($arguments)) {
            $this->fill($arguments[0]);
        }
    }

    public function fill(array $collection)
    {
        parent::__construct($collection);

        $this->seats_per_tour = $collection['seats_per_tour'];
        $this->language = $collection['language'];
        $this->guide = $collection['guide'];
        $this->family_price = $collection['family_price'];
        $this->single_price = $collection['single_price'];
        $this->start_location = $collection['start_location'];
    }
}
