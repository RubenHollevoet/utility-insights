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

$stmt = $dbCon->prepare('DELETE FROM participant WHERE id = :id');
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();

header("Content-Type: application/json; charset=UTF-8");
http_response_code(201);
echo json_encode(['status' => 'ok']);