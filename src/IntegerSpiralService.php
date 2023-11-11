<?php

namespace App;

use PDO;
use Database\Database;

class IntegerSpiralService
{
    private PDO $conn;
    public array $axisX;
    public array $axisY;
    public int $currentNumber = 0;
    public int $lastNumber = 0;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getLayouts(): array
    {
        $sql = "SELECT x,y,value,created_at FROM integer_layout";

        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];

        foreach ($row as $key =>  $value){
            var_dump($key, $value);
        }

        //var_dump($data);

        return [];
    }

    public function getLayoutById(string $id) {
        var_dump("Get the layout with id {$id}");
    }

    public function createLayout( string $x, string $y) : int{

        for($ax = 0; $ax < $x; $ax++){
            $this->axisX[] = $ax;
        }

        for($ay = 0; $ay < $y; $ay++){
            $this->axisY[] = $ay;
        }

        $matrix = [];

        $this->lastNumber = $lastNumber = $x * $y;

        for ($i = 0; $i < $y; $i++){
            $matrix[$i] = array_fill(0, $x, 0);
        }

        do{
            $matrix = $this->fillLeftToRight($matrix, $this->axisY);

            $matrix = $this->fillTopToBottom($matrix, $this->axisX);

            $matrix = $this->fillRightToLeft($matrix, $this->axisY);

            $matrix = $this->fillBottomToTop($matrix, $this->axisX);

        }while($this->currentNumber < $lastNumber);

        $table = '<!DOCTYPE html>
<html>
<head>
    <title>Integer Spiral | Güven Şen</title>
    <style>
        p{
            margin: 0;
        }
        .wrapper{
            padding: 15px; background-color: #202528
        }

        .table-wrapper{
            display: flex;
            flex-direction: row;
        }

        .left-header-wrapper{
            border-top: 1px solid #ced3d8;
            border-bottom: 1px solid #ced3d8;
        }

        .left-header-wrapper > .header{
            text-align: center;
            height: 15px;
        }

        .left-header-wrapper > .header-title{
            text-align: center;
            height: 15px;
            color: #ced3d8;
            border-left: 1px solid #ced3d8;
            border-bottom: 1px solid #ced3d8;
            padding: 10px 15px;
        }

        .header-wrapper{
            display: flex;
            border: 1px solid #ced3d8;
            padding: 0;
        }
        .content-wrapper > .header-wrapper{
            border-left: unset;
        }

        .content-wrapper > .header-wrapper > .header {
            width: 25px;
            height: 15px;
            color: #ced3d8;
            border-left: 1px solid #ced3d8;
            border-right: 1px solid #ced3d8;
        }

        .header {
            color: #ced3d8;
            padding: 10px 15px;
            margin-right: -1px;
            border-left: 1px solid #ced3d8;
            border-right: 1px solid #ced3d8;
        }

        .cell-wrapper{
            display: flex;
        }

        .cell {
            width: 25px;
            height: 15px;
            color: #cbbc6a;
            border-left: 1px solid #ced3d8;
            border-right: 1px solid #ced3d8;
            padding: 10px 15px;
            margin-right: -1px;
        }

        .content-wrapper > .cell-wrapper:last-child .cell {
            border-bottom: 1px solid #ced3d8;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="table-wrapper">
            <div class="left-header-wrapper">
                <div class="header-title"> (index)</div>';

        for ($tlh = 0; $tlh < $y; $tlh ++){
            $table .= "<div class='header'><p>".$tlh."</p></div>";
        }

        $table .='  </div>
            <div class="content-wrapper">
                <div class="header-wrapper">';

        for ($th = 0; $th < $x; $th ++){
            $table .= "<div class='header'><p>".$th."</p></div>";
        }
        $table .=  '</div>';
            foreach ($matrix as $mvalue){
                $table .= "<div class='cell-wrapper'>";
                foreach ($mvalue as $nvalue){
                    $table .= "<div class='cell'><p>" .$nvalue."</p></div>";
                }
                $table .= "</div>";
            }
        $table .='
            </div>
        </div>
    </div>
</body>
</html>';

        $data = json_encode($matrix);

        $sql = "INSERT INTO integer_layout (x,y,value) VALUES ($x, $y, '{$data}')";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function fillLeftToRight($matrix, $axisY): array {
        $currentY = array_shift($axisY);
        $currentNumber = $this->currentNumber;
        foreach ($this->axisX as $value){
            if($currentNumber < $this->lastNumber) {
                $matrix[$currentY][$value] = $currentNumber;
                $currentNumber = $currentNumber + 1;
            }
        }

        $this->axisY = $axisY;
        $this->currentNumber = $currentNumber;
        return $matrix;
    }

    public function fillTopToBottom($matrix, $axisX):array{

        $currentX = $axisX[count($axisX) - 1];
        $currentNumber = $this->currentNumber;

        foreach ($this->axisY as $value){
            if($currentNumber < $this->lastNumber) {
                $matrix[$value][$currentX] = $currentNumber;
                $currentNumber = $currentNumber + 1;
            }
        }

        unset($axisX[count($axisX) - 1]);
        $this->axisX = $axisX;
        $this->currentNumber = $currentNumber;

        return $matrix;
    }

    public function fillRightToLeft($matrix, $axisY): array{
        if(count($axisY) > 0){
            $currentY = $axisY[count($axisY) -1];
            $currentNumber = $this->currentNumber;

            foreach (array_reverse($this->axisX) as $value){
                if($currentNumber < $this->lastNumber){
                    $matrix[$currentY][$value] = $currentNumber;
                    $currentNumber = $currentNumber +1;
                }
            }

            $this->currentNumber = $currentNumber;

            unset($axisY[count($axisY) - 1]);
            $this->axisY = $axisY;
        }

        return $matrix;
    }

    public function fillBottomToTop($matrix, $axisX): array{
        $currentX = array_shift($axisX);
        $currentNumber = $this->currentNumber;
        foreach (array_reverse($this->axisY) AS $value){
            if($currentNumber < $this->lastNumber) {
                $matrix[$value][$currentX] = $currentNumber;
                $currentNumber = $currentNumber + 1;
            }
        }

        $this->axisX = $axisX;
        $this->currentNumber = $currentNumber;

        return $matrix;
    }
}