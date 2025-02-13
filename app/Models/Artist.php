<?php

namespace App\Models;

class Artist
{
    public int $id;
    public string $name;
    public ?string $preview_description;
    public ?string $main_description;
    public ?string $iconic_albums;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->name = $collection['name'];
        $this->preview_description = $collection['preview_description'];
        $this->main_description = $collection['main_description'];
        $this->iconic_albums = $collection['iconic_albums'];
    }
}
