<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'config/Database.php';
include_once 'services/RequestHandler.php';

$database = new Database();
$dbCon = $database->getConnection();

$stmt = $dbCon->prepare('SELECT created_at, power FROM data_green_energy ORDER BY id desc LIMIT 1;');
$stmt->execute();
$dataSolar = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbCon->prepare('SELECT created_at, consumption, production FROM data_electricity_meter ORDER BY id desc LIMIT 1;');
$stmt->execute();
$dataGrid = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json; charset=UTF-8");
http_response_code(201);
echo json_encode([
    'solar' => [
        'power' => $dataSolar[0]['power'] / 1000,
        'dateTime' => $dataSolar[0]['created_at']
    ],
    'grid' => [
        'power' => ($dataGrid[0]['consumption'] - $dataGrid[0]['production']) / 1000,
        'dateTime' => $dataGrid[0]['created_at']
    ]
]);