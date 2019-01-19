<?php

include './vendor/autoload.php';
include './getBearerToken.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
$result = array();
$res = array();
extract($_POST);

$token = getBearerToken();

if (isset($_POST) && !is_null($pslno) && !empty($token)) {


    $dtoken = JWT::decode($token, KEY, array("HS256"));
    $dslno = $dtoken->slno;
    $expire = $dtoken->expire;
    $time = time();

    if ($expire > $time) {

        if ($db::$dbcon) {
//        $dtoken = JWT::decode($token, KEY, array("HS256"));
//        $dslno = $dtoken->slno;
            $dbrest = $db->query("select policyid,category from policies"); //->get();
            if ($dbrest) {
                if ($dbrest->result->rowCount() > 0) {
                    $results = $dbrest->get();
                    foreach ($results as $result) {
                        $res[] = ["policyid" => $result->policyid, "category" => $result->category, "status" => TRUE];
//                    array_push($res, $result);
                    }
                    $retres = ["code" => "200", "msg" => "successfully sends policies", "data" => $res];
//                $retres = ["data" => $retres];
                } else {
                    array_push($retres, ["code" => 201, "msg" => "Not Exists Any Contacts"]);
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
