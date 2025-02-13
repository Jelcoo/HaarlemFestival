<?php

namespace App\Models;

use Carbon\Carbon;

class Event
{
    public int $id;
    public float $vat;
    public Carbon $start_time;
    public Carbon $start_date;
    public Carbon $end_time;
    public Carbon $end_date;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->vat = $collection['vat'];
        $this->start_time = Carbon::parse($collection['start_time']);
        $this->start_date = Carbon::parse($collection['start_date']);
        $this->end_time = Carbon::parse($collection['end_time']);
        $this->end_date = Carbon::parse($collection['end_date']);
    }
}
