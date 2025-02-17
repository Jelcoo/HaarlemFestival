<?php

namespace App\Models;

use Carbon\Carbon;

class Asset
{
    public int $id;
    public string $collection;
    public string $filename;
    public string $mimetype;
    public int $size;
    public string $model;
    public int $model_id;
    public Carbon $created_at;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->collection = $collection['collection'];
        $this->filename = $collection['filename'];
        $this->mimetype = $collection['mimetype'];
        $this->size = $collection['size'];
        $this->model = $collection['model'];
        $this->model_id = $collection['model_id'];
        $this->created_at = Carbon::parse($collection['created_at']);
    }
}
