<?php
include_once "class/City.php";
include_once 'config/database.php';

function answer($results)
{
    if (!empty($results)) {
        http_response_code(200);
        echo json_encode(["method" => "GET", "res" => $results], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Данные не найдены."], JSON_UNESCAPED_UNICODE);
    }
}

function route($method, $urlData, $formData)
{
    $database = new Database();
    $db = $database->getConnection();
    $city = new City($db);
    if ($method === "GET" && $urlData[0] === "city" && count($urlData) === 1) {
        $res = $city->getCities();
        answer($res);

        return;
    }

    if ($method === "GET" && $urlData[0] === "city" && $urlData[1] === "sights" && count($urlData) === 2) {
        $res = $city->getCitySights();
        answer($res);

        return;
    }

    //какие путешественники побывали в городе
    if ($method === "GET" && $urlData[0] === "city" && count($urlData) === 2) {
        $res = $city->getCityTravelers($urlData[1]);
        answer($res);

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode([
        'error' => 'Bad Request'
    ]);

}