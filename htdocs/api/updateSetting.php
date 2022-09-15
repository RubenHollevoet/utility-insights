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
$stmt = $dbCon->prepare('UPDATE settings SET `value` = :value WHERE `key` = :key');
$stmt->bindParam(':key', $_GET['key'], PDO::PARAM_STR);
$stmt->bindParam(':value', $_GET['value'], PDO::PARAM_STR);
$stmt->execute();

header("Location: https://example.com/myOtherPage.php");