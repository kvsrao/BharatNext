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

if (isset($_POST) && !is_null($token) && !empty($pslno) ) {

    if ($db::$dbcon) {
        $dtoken = JWT::decode($token, KEY, array("HS256"));
        $dslno = $dtoken->slno;
        $expire = $dtoken->expire;
        $time = time();
        if ($expire > $time) {
            $dbrest = $db->query("select * from policyreg where referred_id = :referred", [":referred" => $pslno]); //->get();
            if ($dbrest) {
                if ($dbrest->result->rowCount() > 0) {
                    $results = $dbrest->get();
                    foreach ($results as $result) {
                        $res[] = $result;
//                    array_push($res, $result);
                    }
                    $retres = ["code" => "200", "msg" => "successfully sends referrels", "data" => $res];
//                $retres = ["data" => $retres];
                } else {
                    $retres = ["code" => 201, "msg" => "Not Exists Any Contacts"];
                }
            } else {
                $retres = ["code" => 202, "msg" => "Table does't exists"];
            }
        } else {
            $retres = ["code" => 205, "msg" => "Your Session Hasbeen Expired"];
        }
    } else {
        $retres = ["code" => 204, "msg" => "wrong db credentials"];
    }
} else {
    $retres = ["code" => 203, "msg" => "values should not be is_null"];
}
echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
