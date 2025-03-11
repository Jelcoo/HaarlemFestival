<?php

namespace App\Models;

class Restaurant
{
    public int $id;
    public int $location_id;
    public string $restaurant_type;
    public int $rating;
    public ?string $menu;
    public ?array $assets;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->location_id = $collection['location_id'];
        $this->restaurant_type = $collection['restaurant_type'];
        $this->rating = $collection['rating'];
        $this->menu = $collection['menu'];
    }
}
