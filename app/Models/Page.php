<?php

namespace App\Models;

use Carbon\Carbon;

class Page
{
    public int $id;
    public string $name;
    public string $header_text;
    public string $slug;
    public Carbon $created_at;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->name = $collection['name'];
        $this->header_text = $collection['header'];
        $this->slug = $collection['slug'];
        $this->created_at = Carbon::parse($collection['created_at']);
    }
}
