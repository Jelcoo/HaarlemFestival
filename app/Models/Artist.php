<?php

namespace App\Models;

use Carbon\Carbon;

class Artist
{
    public int $id;
    public string $name;
    public string|null $preview_description;
    public string|null $main_description;
    public string|null $iconic_albums;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->name = $collection['name'];
        $this->preview_description = $collection['preview_description'];
        $this->main_description = $collection['main_description'];
        $this->iconic_albums = $collection['iconic_albums'];
    }
}
