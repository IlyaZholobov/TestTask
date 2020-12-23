<?php
include_once "class/Traveler.php";
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
    $traveler = new Traveler($db);
    if ($method === "GET" && $urlData[0] === "traveler" && count($urlData) === 1) {
        $res = $traveler->getTraveler();
        answer($res);

        return;
    }

    //какие города посетил путешественник
    if ($method === "GET" && $urlData[0] === "traveler" && count($urlData) === 2) {
        $res = $traveler->getTravelerCities($urlData[1]);
        answer($res);

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode([
        'error' => 'Bad Request'
    ]);

}