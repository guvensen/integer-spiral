<?php

namespace App;

use PDO;
use Database\Database;

class IntegerSpiralService
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getLayouts()
    {
        var_dump('Get Layouts');
    }

    public function getLayoutById(string $id) {
        var_dump("Get the layout with id {$id}");
    }

    public function createLayout(){
        var_dump('Create Layout');
    }
}