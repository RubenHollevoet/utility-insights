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

$stmt = $dbCon->prepare('INSERT INTO participant (`name`, `url`) VALUES (:name, :url)');
$stmt->bindParam(':name', $_GET['name'], PDO::PARAM_STR);
$stmt->bindParam(':url', $_GET['url'], PDO::PARAM_STR);
$stmt->execute();

header("Content-Type: application/json; charset=UTF-8");
http_response_code(201);
echo json_encode(['status' => 'ok']);