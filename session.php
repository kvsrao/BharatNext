<?php

include './vendor/autoload.php';
include './getBearerToken.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
extract($_POST);
$token = getBearerToken();

if (isset($_POST) && !is_null($token) && !empty($token)) {

//    $expire = time() + (15 * 24 * 60 * 60 * 1000);
//    $payload = ["name" => $result->name, "slno" => $result->slno, "email" => $result->email, "expire" => $expire];
//    $token = JWT::encode($payload, KEY);

    $dtoken = JWT::decode($token, KEY, array("HS256"));
    $dslno = $dtoken->slno;
    $dexpire = $dtoken->expire;
    $dname = $dtoken->name;
    $demail = $dtoken->email;
    if ($dexpire > time()) {
        $retres = ["code" => 200, "msg" => "You are Welcome"];
    } else {
        $retres = ["code" => 201, "msg" => "Your Session Has been expired"];
    }
} else {
    array_push($retres, ["code" => 203, "msg" => "values should not be is_null"]);
}
echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
