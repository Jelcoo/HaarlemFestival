<?php

namespace App\Models;

use App\Enum\EventTypeEnum;
use Carbon\Carbon;

class EventHistory extends Event
{
    public int $id;
    public int $event_id;
    public int $seats_per_tour;
    public string $language;
    public string $guide;
    public float $family_price;
    public float $single_price;
    public string $start_location;

    public function __construct(array $collection)
    {
        $this->id = $collection["id"];
        $this->event_id = $collection["event_id"];
        $this->seats_per_tour = $collection["seats_per_tour"];
        $this->language = $collection["language"];
        $this->guide = $collection["guide"];
        $this->single_price = $collection["single_price"];
        $this->start_location = $collection["start_location"];
    }
}
