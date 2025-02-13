<?php

namespace App\Models;

use Carbon\Carbon;

class Restaurant
{
    public int $id;
    public int $location_id;
    public string $name;
    public string $restaurant_type;
    public int $rating;
    public string|null $preview_description;
    public string|null $main_description;
    public string|null $menu;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->name = $collection['name'];
        $this->restaurant_type = $collection['restaurant_type'];
        $this->rating = $collection['rating'];
        $this->preview_description = $collection['preview_description'];
        $this->main_description = $collection['main_description'];
        $this->menu = $collection['menu'];
    }
}
