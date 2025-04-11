<?php
require "vendor/autoload.php";

$api = new \AG\Presentation\API\GenerateArmyAPI();
$response = $api->run($_GET);
header('Content-Type: application/json');
echo json_encode($response);