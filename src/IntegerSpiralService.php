<?php

namespace App;

use PDO;
use Database\Database;
use Exception;

/**
 * @OA\Info(
 *     title="Integer Spiral API",
 *     version="0.0.1"
 * )
 */
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

    /**
     * @OA\Get(
     *   path="/layout",
     *   tags={"Layout"},
     *   summary="List layouts",
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="layout not found"
     *   )
     * )
     */
    public function getLayouts(): string
    {
        try {
            $sql = "SELECT id,x,y FROM integer_layout ORDER BY id ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!$row){
                throw new Exception("Layout not found.", 404);
            }
        }catch (Exception $e){
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit;
        }

        return json_encode($row);
    }

    /**
     * @OA\Get(
     *   path="/layout/{layoutId}",
     *   tags={"Layout"},
     *   summary="Find layout by ID",
     *   @OA\Parameter(
     *       in="path",
     *       required=true,
     *       name="layoutId",
     *       description="The layout ID specific to this layout",
     *       @OA\Schema(
     *         type="integer"
     *      ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="layout not found"
     *   )
     * )
     */
    public function getLayoutById(string $id): string
    {
        try {
            $sql = "SELECT id,x,y FROM integer_layout WHERE id = {$id}";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$row){
                throw new Exception("Layout not found.", 404);
            }
        }catch (Exception $e){
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit;
        }

        return json_encode($row);
    }

    /**
     * @OA\Get(
     *   path="/layout/{layoutId}/value",
     *   tags={"Layout"},
     *   summary="Get value of layout",
     *   @OA\Parameter(
     *       in="path",
     *       required=true,
     *       name="layoutId",
     *       description="The layout ID specific to this layout",
     *       @OA\Schema(
     *         type="integer"
     *      ),
     *   ),
     *   @OA\Parameter(
     *       in="query",
     *       required=true,
     *       name="x",
     *       description="X coordinate of the layout",
     *       @OA\Schema(
     *         type="integer"
     *      ),
     *   ),
     *   @OA\Parameter(
     *       in="query",
     *       required=true,
     *       name="y",
     *       description="Y coordinate of the layout",
     *       @OA\Schema(
     *         type="integer"
     *      ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="layout not found"
     *   ),
     *   @OA\Response(
     *     response=412,
     *     description="x or y coordinate is incorrect"
     *   )
     * )
     */
    public function getValueOfLayout(string $id, string $x, string $y): string
    {
        try {
            $sql = "SELECT x,y,value FROM integer_layout WHERE id = {$id}";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$row){
                throw new Exception("Layout not found.", 404);
            }

            $matrix = json_decode($row["value"]);
            $layoutX = $row["x"];
            $layoutY= $row["y"];

            if($x >= $layoutX || $y >= $layoutY){
                throw new Exception("X or Y coordinate is incorrect.", 412);
            }
        }catch (Exception $e){
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit;
        }

        return $matrix[$y][$x];
    }

    public function getLayoutByIdWithTable(string $id): string {
        try {
            $sql = "SELECT x,y,value FROM integer_layout WHERE id = {$id}";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            if(!$row){
                throw new Exception("Layout not found.", 404);
            }

            $matrix = json_decode($row["value"]);
            $x = $row["x"];
            $y = $row["y"];

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

        }catch (Exception $e){
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit;
        }

        return $table;
    }

    /**
     * @OA\Post(
     *   path="/layout",
     *   tags={"Layout"},
     *   summary="Add a new layout",
     *   @OA\Parameter(
     *       in="query",
     *       required=true,
     *       name="x",
     *       description="X coordinate of the layout",
     *       @OA\Schema(
     *         type="integer"
     *      ),
     *   ),
     *   @OA\Parameter(
     *        in="query",
     *        required=true,
     *        name="y",
     *        description="Y coordinate of the layout",
     *        @OA\Schema(
     *          type="integer"
     *       ),
     *    ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation"
     *   ),
     *   @OA\Response(
     *     response=412,
     *     description="x or y coordinate is incorrect"
     *   )
     * )
     */
    public function createLayout( string | null $x, string | null $y) : int{
        try {
            if(is_null($x) || is_null($y)){
                throw new Exception("X or Y coordinate is incorrect.", 412);
            }

            if(!is_numeric($x) || !is_numeric($y) || strpos($x, ".") || strpos($y, ".")){
                throw new Exception("Coordinates must be integer.", 412);
            }

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

            $data = json_encode($matrix);

            $sql = "INSERT INTO integer_layout (x,y,value) VALUES ($x, $y, '{$data}')";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute();

        }catch (Exception $e){
            http_response_code($e->getCode());
            echo $e->getMessage();
            exit;
        }

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