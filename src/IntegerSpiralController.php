<?php

namespace App;

use \App\IntegerSpiralService AS IntegerSpiralService;
use Database\Database;
use Exception;

class IntegerSpiralController
{
    private $service;

    public function __construct(Database $database){
        $this->service = new IntegerSpiralService($database);
    }

    public function index(string $method, string $query, ?string $id): string | object | array
    {
        $result = "";
        switch ($method){
            case 'GET':
                if ($id){
                    $this->service->getLayoutById($id);
                }else{
                    $result =  $this->service->getLayouts();
                }
                break;
            case 'POST':
                $x = array_key_exists('x', $_GET) ? $_GET['x'] : null;
                $y = array_key_exists('y', $_GET) ? $_GET['y'] : null;

                try{
                    if(is_null($x) || is_null($y)){
                        throw new Exception("", 412);
                    }
                }catch (Exception $e){
                    http_response_code(412);
                    exit;
                }
                $result = $this->service->createLayout($x,$y);
                break;
        }

        return $result;
    }
}