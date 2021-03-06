<?php

include './vendor/autoload.php';
include './getBearerToken.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
$results = array();
$result = array();
$res = array();
$token = getBearerToken();

extract($_POST);

if (isset($_POST) && !is_null($token) && !is_null($jobid)) {

    $dtoken = JWT::decode($token, KEY, array("HS256"));
    $dslno = $dtoken->slno;
    $expire = $dtoken->expire;
    $time = time();
    if ($expire > $time) {
        if ($db::$dbcon) {
            $dbrest = $db->query("select * from notifications where jobid = $jobid"); //->get();
            if ($dbrest) {
                if ($dbrest->result->rowCount() > 0) {
                    $results = $dbrest->get();
                    foreach ($results as $result) {
                        array_push($res, $result);
                    }
                    $retres = ["code" => "200", "msg" => "Notifications Sends Successfully", "notifications" => $res];
                    $retres = ["data" => $retres];
                } else {
                    array_push($retres, ["code" => 201, "msg" => "No Notifications"]);
                }
            } else {
                array_push($retres, ["code" => 202, "msg" => "Table does't exists"]);
            }
        } else {
            array_push($retres, ["code" => 204, "msg" => "wrong db credentials"]);
        }
    } else {
        $retres = ["code" => 205, "msg" => "Your Session Hasbeen Expired"];
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
