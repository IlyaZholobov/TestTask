<?php

class City
{
    private $conn;
    private $table_name = "city";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * @return array
     */
    function getCities():array
    {
        $query = "SELECT * FROM  " . $this->table_name;

        // подготовка запроса
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();
        $cities = [];

        if ($num > 0) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);
                $city = [
                    "id" => $row["id"],
                    "name" => $row["name"],
                ];

                array_push($cities, $city);
            }
        }

        return $cities;
    }

    /**
     *
     * @param int $idCity
     * @return array
     * какие путешественники побывали в городе
     */
    function getCityTravelers($idCity):array
    {

        $query = "SELECT DISTINCT t.name travelerName
            FROM " . $this->table_name . " c
            JOIN visit v ON v.id_city=c.id
            JOIN traveler t ON t.id=v.id_traveler
            WHERE c.id =" . $idCity;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();
        $travelers = [];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);
                $traveler = $row['travelerName'];
                print_r($row);
                array_push($travelers, $traveler);
            }
        }

        return $travelers;
    }

    /**
     * в каких городах какие достопримечательности
     * @return array
     */
    function getCitySights():array
    {
        $query = "SELECT c.name nameCity,GROUP_CONCAT(s.name) sights
            FROM " . $this->table_name . " c
            JOIN sight s ON s.id_city=c.id
            GROUP BY c.name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();
        $citiesSights = [];
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $citySights = [
                    "nameCity" => $row["nameCity"],
                    "sights" => $row["sights"],
                ];
                array_push($citiesSights, $citySights);
            }
        }

        return $citiesSights;
    }
}