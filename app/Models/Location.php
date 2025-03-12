<?php

namespace App\Models;

use App\Enum\EventTypeEnum;

class Location
{
    public int $id;
    public string $name;
    public EventTypeEnum $event_type;
    public ?string $coordinates;
    public ?string $address;
    public ?string $preview_description;
    public ?string $main_description;
    public ?array $assets = [];

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->name = $collection['name'];
        $this->event_type = EventTypeEnum::from($collection['event_type']);
        $this->coordinates = $collection['coordinates'];
        $this->address = $collection['address'];
        $this->preview_description = $collection['preview_description'];
        $this->main_description = $collection['main_description'];
    }
}
