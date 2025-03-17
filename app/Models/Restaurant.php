<?php

namespace App\Models;

class Restaurant
{
    public int $id;
    public int $location_id;
    public string $restaurant_type;
    public int $rating;
    public ?string $menu;
    public ?array $assets = [];
    public ?Location $location;

    public function __construct()
    {
        $arguments = func_get_args();

        if (!empty($arguments)) {
            $this->fill($arguments[0]);
        }
    }

    public function fill(array $collection)
    {
        $this->id = $collection['id'];
        $this->location_id = $collection['location_id'];
        $this->restaurant_type = $collection['restaurant_type'];
        $this->rating = $collection['rating'];
        $this->menu = $collection['menu'];
    }
}
