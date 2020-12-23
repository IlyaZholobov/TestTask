<?php

class Sight
{
    private $conn;
    private $table_name = "sight";

    // конструктор для соединения с базой данных
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * @return array
     */
    function getSights(): array
    {
        $query = "SELECT s.id, c.name nameCity, s.name name,s.`distance` , avg(v.rating) rating
        FROM visit v 
        JOIN city c ON v.id_city=c.id
        JOIN " . $this->table_name . " s ON s.id_city=c.id
        WHERE v.id_city=c.id AND v.id_sight=s.id
        GROUP BY s.id, c.name, s.name ,s.distance
        ORDER BY avg(v.rating) DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        $sights = [];
        if ($num > 0) {

            $sights = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $sight = [
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "name_city" => $row["nameCity"],
                    "distance" => $row["distance"],
                    "rating" => $row["rating"]
                ];

                array_push($sights, $sight);
            }
        }
        return $sights;
    }
}
