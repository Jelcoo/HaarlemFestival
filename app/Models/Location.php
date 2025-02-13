<?php

namespace App\Models;

use Carbon\Carbon;

class Location
{
    public int $id;
    public string $name;
    public string|null $coordinates;
    public string|null $address;
    public string|null $preview_description;
    public string|null $main_description;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->name = $collection['name'];
        $this->coordinates = $collection['coordinates'];
        $this->address = $collection['address'];
        $this->preview_description = $collection['preview_description'];
        $this->main_description = $collection['main_description'];
    }
}
