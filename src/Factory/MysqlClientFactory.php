<?php

declare(strict_types=1);

namespace App\Factory;

use Simplon\Mysql\Mysql;
use Simplon\Mysql\PDOConnector;

class MysqlClientFactory
{
    private string $host;

    private string $user;

    private string $password;

    private string $database;

    public function __construct(
        string $host,
        string $user,
        string $password,
        string $database
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public function create(): Mysql
    {
        $pdo = new PDOConnector(
            $this->host,
            $this->user,
            $this->password,
            $this->database
        );

        $pdoConn = $pdo->connect('utf8', []);

        return new Mysql($pdoConn);
    }
}