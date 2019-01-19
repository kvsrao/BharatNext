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
extract($_POST);

if (isset($_POST) && !is_null($token)) {

    if ($db::$dbcon) {
        $dbrest = $db->query("select name,mobile from users"); //->get();
        if ($dbrest) {
            if ($dbrest->result->rowCount() > 0) {
                $results = $dbrest->get();
                foreach ($results as $result) {
                    array_push($res, $result);
                }

//                $expire = time() + (15 * 24 * 60 * 60 * 1000);
//                $payload = ["name" => $result->name, "slno" => $result->slno, "email" => $result->email, "expire" => $expire];
//                $token = JWT::encode($payload, KEY);
//                $dtoken = JWT::decode($token, KEY, array("HS256"));
//                $dslno = $dtoken->slno;
//                $dexpire = $dtoken->expire;
//                $dname = $dtoken->name;
//                $demail = $dtoken->email;
//                $dbfire = $db->query("update users set firebaseid = :firebaseid where slno = :slno", [":firebaseid" => $firebase_id, ":slno" => $result->slno]);
////                array_push($retres1, ["code" => 200, "msg" => "Successfully Logged In", "name" => $result->name, "mobile_no" => $result->mobile, "email_id" => $result->email, "slno" => $result->slno, "token" => $token]);
//                $retres1 = ["code" => 200, "msg" => "Successfully Logged In", "name" => $result->name, "mobile_no" => $result->mobile, "email_id" => $result->email, "slno" => $result->slno, "token" => $token];
//                $dbuserdet = $db->query("select * from registrations where userid = :slno", [':slno' => $result->slno]); //->get();
//                if ($dbuserdet->result->rowCount() > 0) {
//                    $result = $dbuserdet->get()[0];
//                    $result2 = $result;
////                    array_push($retres, ["details" => $result]);
//                }
//                array_push($retres,["data"=>$retres1,"det"ails"=>$result2]);
                $retres = ["code" => "200","msg"=>"successfully sends contacts", "contacts" => $res];
                $retres = ["data" => $retres];
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
    array_push($retres, ["code" => 203, "msg" => "values should not be is_null"]);
}
echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
