<?php

namespace App\Repositories;

use App\Config\Config;
use App\Application\Response;
use App\Controllers\ErrorController;

class Repository
{
    protected \PDO $pdoConnection;

    public function __construct()
    {
        $this->connect();
    }

    protected function getConnection(): \PDO
    {
        return $this->pdoConnection;
    }

    private function connect(): \PDO
    {
        $host = Config::getKey('DB_HOST');
        $port = Config::getKey('DB_PORT');
        $database = Config::getKey('DB_NAME');
        $username = Config::getKey('DB_USER');
        $password = Config::getKey('DB_PASSWORD');

        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdoConnection = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            if (Config::getKey('APP_ENV') === 'development') {
                throw $e;
            }
            $response = new Response();
            $response->setStatusCode(500);
            $response->setContent((new ErrorController())->error500($e->getMessage()));
            $response->sendJson();
            exit;
        }

        return $this->pdoConnection;
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->getConnection()->prepare($sql);
    }
}
