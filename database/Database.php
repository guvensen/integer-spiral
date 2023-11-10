<?php

namespace Database;

use PDO;

readonly class Database
{
    public function __construct( private string $connection,
                                 private string $host,
                                 private string $port,
                                 private string $name,
                                 private string $user,
                                 private string $password
    ) {}

    public function getConnection(): PDO
    {
        $dsn = "{$this->connection}:host={$this->host};port={$this->port};dbname={$this->name}";

        return new PDO($dsn, $this->user, $this->password);
    }
}