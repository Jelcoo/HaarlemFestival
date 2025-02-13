<?php

namespace App\Models;

use App\Enum\EventTypeEnum;
use Carbon\Carbon;

class Event
{
    public int $id;
    public int $page_id;
    public EventTypeEnum $event_type;
    public string $session;
    public Carbon $start;
    public Carbon $end;
    public Carbon $created_at;

    public function __construct(array $collection)
    {
        $this->id = $collection["id"];
        $this->page_id = $collection["page"];
        $this->event_type = EventTypeEnum::from($collection["type"]);
        $this->session = $collection["session"];
        $this->start = Carbon::parse($collection["start"]);
        $this->end = Carbon::parse($collection["end"]);
        $this->created_at = Carbon::parse($collection["created_at"]);
    }
}
