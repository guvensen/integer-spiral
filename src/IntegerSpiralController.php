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

    public function index(string $method, string $query, ?string $id, array $path): string|object|array
    {
        $result = "";
        switch ($method) {
            case 'GET':
                if ($id) {
                    $feature = array_key_exists(3, $path) ? $path[3] : null;

                    if ($feature) {
                        switch ($feature) {
                            case 'tabular':
                                $result = $this->service->getLayoutByIdWithTable($id);
                                break;
                            case 'value':
                                header("Content-type: application/json; charset=UTF-8");
                                $x = array_key_exists('x', $_GET) ? $_GET['x'] : null;
                                $y = array_key_exists('y', $_GET) ? $_GET['y'] : null;

                                $result = $this->service->getValueOfLayout($id, $x, $y);
                                break;
                        }
                    } else {
                        header("Content-type: application/json; charset=UTF-8");
                        $result = $this->service->getLayoutById($id);
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