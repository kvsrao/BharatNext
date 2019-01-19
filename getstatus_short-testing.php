<?php

include './vendor/autoload.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
$result = array();
$res = array();
extract($_GET);

$pslno = "";

$rest = file_get_contents("http://localhost/BharatNext/getstatus_short.php?slno=1");
//$rest = file_get_contents("http://localhost/BharatNext/getstatus_short.php?slno=1");

echo json_encode($rest);