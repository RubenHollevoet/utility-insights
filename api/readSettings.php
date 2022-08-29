<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'config/Database.php';

$database = new Database();
$dbCon = $database->getConnection();

//$stmt = $dbCon->prepare("SELECT created_at, power FROM data_green_energy");
$stmt = $dbCon->prepare('SELECT `key`, `value` FROM settings');
$stmt->execute();

//$data = [];
//foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $line) {
//    $data[$line['created_at']] = $line['power'];
//}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json; charset=UTF-8");
http_response_code(201);
echo json_encode($data);