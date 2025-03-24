<?php

namespace App\Models;

class EventYummy extends Event
{
    public int $restaurant_id;
    public int $total_seats;
    public float $kids_price;
    public float $adult_price;
    public float $reservation_cost;
    public ?Restaurant $restaurant = null;

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

        $this->restaurant_id = $collection['restaurant_id'];
        $this->total_seats = $collection['total_seats'];
        $this->kids_price = $collection['kids_price'];
        $this->adult_price = $collection['adult_price'];
        $this->reservation_cost = $collection['reservation_cost'];
    }
}
