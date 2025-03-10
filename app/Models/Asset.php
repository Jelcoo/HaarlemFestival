<?php

namespace App\Models;

use App\Config\Config;

class Asset
{
    public int $id;
    public string $collection;
    public string $filename;
    public string $mimetype;
    public int $size;
    public ?string $model;
    public ?int $model_id;
    public string $created_at;

    public function __construct()
    {
        $arguments = func_get_args();

        if (!empty($arguments)) {
            $this->fill($arguments[0]);
        }
    }

    public function fill(array $collection)
    {
        $this->id = $collection['id'];
        $this->collection = $collection['collection'];
        $this->filename = $collection['filename'];
        $this->mimetype = $collection['mimetype'];
        $this->size = $collection['size'];
        $this->model = $collection['model'];
        $this->model_id = $collection['model_id'];
        $this->created_at = $collection['created_at'];
    }

    public function getUrl(): string
    {
        return Config::getKey('APP_URL') . '/storage/' . $this->filename;
    }
}
