<?php

class Traveler
{
    private $conn;
    private $table_name = "traveler";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * @return array
     */
    function getTraveler(): array
    {
        $query = "SELECT t.id, t.name, c.name cityName, s.name sightName, avg(v.rating) rating
            FROM visit v 
            JOIN city c ON v.id_city=c.id
            JOIN sight s ON v.id_city=c.id
            JOIN " . $this->table_name . " t ON t.id=v.id_traveler
            WHERE v.id_city=c.id AND v.id_sight=s.id
            GROUP BY  t.id,t.name, c.name, s.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();
        $travelers = [];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $traveler = [
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "city" => $row["cityName"],
                    "sightName" => $row["sightName"],
                    "sightRate" => $row["rating"],
                ];
                array_push($travelers, $traveler);
            }
        }

        return $travelers;
    }

    /**
     * @param $idtraveler
     * @return array
     * какие города посетил путешественник
     */
    function getTravelerCities($idtraveler): array
    {
        $query = "SELECT t.name,GROUP_CONCAT( distinct c.name) cites
            FROM " . $this->table_name . " t
            JOIN visit v ON v.id_traveler=t.id
            JOIN city c ON c.id=v.id_city
            WHERE t.id = " . $idtraveler . "
            GROUP BY t.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();
        $travelercities = [];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $travelercity = [
                    "name" => $row["name"],
                    "city" => $row["cites"],
                ];

                array_push($travelercities, $travelercity);
            }
        }

        return $travelercities;
    }
}