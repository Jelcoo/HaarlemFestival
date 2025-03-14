<?php

namespace App\Config;

class Config
{
    public static function getKey(string $key, mixed $default = null): mixed
    {
        $config = require __DIR__ . '/../../config.php';

        return $config[$key] ?? $default;
    }
}
