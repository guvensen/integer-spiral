<?php

namespace App;

use \App\IntegerSpiralService as IntegerSpiralService;
use Database\Database;

class IntegerSpiralController
{
    private IntegerSpiralService $service;

    public function __construct(Database $database)
    {
        $this->service = new IntegerSpiralService($database);
    }

    public function index(string $method, string $query, ?string $id): string|object|array
    {
        $result = "";
        switch ($method) {
            case 'GET':
                if ($id) {
                    $isTable = array_key_exists('isTable', $_GET) ? $_GET['isTable'] : null;

                    if ($isTable === "1") {
                        $result = $this->service->getLayoutByIdWithTable($id);
                    } else {
                        header("Content-type: application/json; charset=UTF-8");
                        $x = array_key_exists('x', $_GET) ? $_GET['x'] : null;
                        $y = array_key_exists('y', $_GET) ? $_GET['y'] : null;

                        if (!is_null($x) && !is_null($y)) {
                            $result = $this->service->getValueOfLayout($id, $x, $y);
                        } else {
                            $result = $this->service->getLayoutById($id);
                        }
                    }
                } else {
                    header("Content-type: application/json; charset=UTF-8");
                    $result = $this->service->getLayouts();
                }
                break;
            case 'POST':
                header("Content-type: application/json; charset=UTF-8");
                $x = array_key_exists('x', $_GET) ? $_GET['x'] : null;
                $y = array_key_exists('y', $_GET) ? $_GET['y'] : null;

                $result = $this->service->createLayout($x, $y);
                break;
        }

        return $result;
    }
}