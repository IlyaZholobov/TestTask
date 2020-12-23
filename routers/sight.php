<?php
include_once "class/Sight.php";
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
    $sight = new Sight($db);

    if ($method === "GET" && $urlData[0] === "sight" && count($urlData) === 1) {
        $res = $sight->getSights();
        answer($res);

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode([
        'error' => 'Bad Request'
    ]);

}