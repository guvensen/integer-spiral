<?php

namespace App;

use \App\IntegerSpiralService AS IntegerSpiralService;
use Database\Database;

class IntegerSpiralController
{
    private $service;

    public function __construct(Database $database){
        $this->service = new IntegerSpiralService($database);
    }

    public function index(string $method, ?string $id): void
    {
        switch ($method){
            case 'GET':
                if ($id){
                    $this->service->getLayoutById($id);
                }else{
                    $this->service->getLayouts();
                }
                break;
            case 'POST':
                $this->service->createLayout();
                break;
        }
    }
}