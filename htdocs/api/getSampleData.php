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

$requestHandler = new RequestHandler(['from']);


// get posted data
$from = new DateTime($requestHandler->getParameter('from'));
$from = $from->format('Y-m-d H:i:s');
$to = new DateTime($requestHandler->getParameter('to'));
$to = $to->format('Y-m-d H:i:s');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$stmt = $dbCon->prepare('SELECT created_at, power FROM data_green_energy WHERE created_at > :from AND created_at < :to;');
$stmt->bindParam(':from', $from, PDO::PARAM_STR);
$stmt->bindParam(':to', $to, PDO::PARAM_STR);
$stmt->execute();
$dataSolar = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbCon->prepare('SELECT created_at, consumption, production FROM data_electricity_meter WHERE created_at > :from AND created_at < :to;');
$stmt->bindParam(':from', $from, PDO::PARAM_STR);
$stmt->bindParam(':to', $to, PDO::PARAM_STR);
$stmt->execute();
$dataGrid = $stmt->fetchAll(PDO::FETCH_ASSOC);

$processedData = [
    'solar' => [],
    'consumption' => [],
    'production' => []
];
if($maxSamples = $requestHandler->getParameter('maxSamples')) {
    //solar
    $groupSamples = ceil(count($dataSolar)/$maxSamples);

    $avgPower = 0;
    $avgUnixTime = 0;

    $dateTimeZone = new DateTimeZone(date_default_timezone_get());
    $currentDateTime = new DateTime("now", $dateTimeZone);
    $timeOffset = $dateTimeZone->getOffset($currentDateTime);

    for($i = 0, $iMax = count($dataSolar); $i < $iMax; ++$i) {
        $dateTime = new DateTime($dataSolar[$i]['created_at']);
        $avgPower += $dataSolar[$i]['power'];
        $avgUnixTime += $dateTime->getTimeStamp() + $timeOffset;

        if(0 === (($i + 1) % $groupSamples)) {
            $avgPower /= $groupSamples;
            $avgUnixTime /= $groupSamples;

            $processedData['solar'][] = [(int) $avgUnixTime * 1000, round($avgPower)];

            $avgPower = 0;
            $avgUnixTime = 0;
        }
    }

    //grid
    $groupSamples = ceil(count($dataGrid)/$maxSamples);

    $avgConsumption = 0;
    $avgProduction = 0;
    $avgUnixTime = 0;

    $dateTimeZone = new DateTimeZone(date_default_timezone_get());
    $currentDateTime = new DateTime("now", $dateTimeZone);
    $timeOffset = $dateTimeZone->getOffset($currentDateTime);

    for($i = 0, $iMax = count($dataGrid); $i < $iMax; ++$i) {
        $dateTime = new DateTime($dataGrid[$i]['created_at']);
        $avgConsumption += $dataGrid[$i]['consumption'];
        $avgProduction += $dataGrid[$i]['production'];
        $avgUnixTime += $dateTime->getTimeStamp() + $timeOffset;

        if(0 === (($i + 1) % $groupSamples)) {
            $avgConsumption /= $groupSamples;
            $avgProduction /= $groupSamples;
            $avgUnixTime /= $groupSamples;

            $processedData['consumption'][] = [(int) $avgUnixTime * 1000, round($avgConsumption)];
            $processedData['production'][] = [(int) $avgUnixTime * 1000, round($avgProduction)];

            $avgConsumption = 0;
            $avgProduction = 0;
            $avgUnixTime = 0;
        }
    }
}
else {
    //solar
    foreach ($dataSolar as $sample) {
        $dateTime = new DateTime($sample['created_at']);
        $processedData['solar'][$dateTime->getTimestamp()] = $sample['power'];
    }

    //grid
    foreach ($dataGrid as $sample) {
        $dateTime = new DateTime($sample['created_at']);
        $processedData['production'][$dateTime->getTimestamp()] = $sample['production'];
        $processedData['consumption'][$dateTime->getTimestamp()] = $sample['consumption'];
    }
}

header("Content-Type: application/json; charset=UTF-8");
http_response_code(201);
echo json_encode($processedData);