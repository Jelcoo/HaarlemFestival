<?php

namespace App\Models;

class EventYummy extends Event
{
    public int $id;
    public int $event_id;
    public int $restaurant;
    public int $restaurant_type;
    public float $rating;
    public float $kids_price;
    public float $adult_price;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->restaurant = $collection['restaurant'];
        $this->restaurant_type = $collection['restaurant_type'];
        $this->restaurant_type = $collection['restaurant_type'];
        $this->rating = $collection['rating'];
        $this->kids_price = $collection['kids_price'];
        $this->adult_price = $collection['adult_price'];
    }
}
