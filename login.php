<?php

include './vendor/autoload.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
$retres1 = array();
$retres2 = array();
extract($_POST);

if (isset($_POST) && !is_null($userid) && !is_null($password) && !is_null($firebase_id)) {

    if ($db::$dbcon) {
        $dbrest = $db->query("select * from users where password = :password and (email = :userid or mobile = :userid)", [':userid' => $userid, ':password' => $password]); //->get();
        if ($dbrest) {
            if ($dbrest->result->rowCount() > 0) {
                $result = $dbrest->get()[0];
                $expire = time() + ( 15 * 24 * 60 * 60 );
//                $expire = time() + 30;
                $payload = ["name" => $result->name, "slno" => $result->slno, "email" => $result->email, "expire" => $expire];
                $token = JWT::encode($payload, KEY);

                $dbfire = $db->query("update users set firebaseid = :firebaseid where slno = :slno", [":firebaseid" => $firebase_id, ":slno" => $result->slno]);

                $retres1 = ["name" => $result->name, "mobile_no" => $result->mobile, "email_id" => $result->email, "password" => $result->password, "slno" => $result->slno, "token" => $token];

                $dbuserdet = $db->query("select * from registrations where userid = :slno", [':slno' => $result->slno]); //->get();

                if ($dbuserdet->result->rowCount() > 0) {
                    $result = $dbuserdet->get()[0];
                    $result2 = $result;
                }


                $policydb = $db->query("select * from policyreg where mobile = :mobile", [":mobile" => $retres1["mobile_no"] ] );

                if ($policydb->result->rowCount() > 0) {
                    $result = $policydb->get()[0];
                    $result3 = $result;
                }

                $dbrestref = $db->query("select * from policyreg where referred_id = :referred", [":referred" => $result3->pslno ]); //->get();
                if ($dbrestref->result->rowCount() > 0) {
                    $results = $dbrestref->get();
                    foreach ($results as $result) {
                        $referls[] = $result;
                    }
                } 

                $res1 = ["status" => $retres1, "details" => $result2, "policy" => $result3,"referls"=>$referls];

                $retres = ["code" => 200, "msg" => "Successfully Logged In", "data" => $res1];
            } else {
                $retres = ["code" => 201, "msg" => "Login Fails"];
            }
        } else {
            $retres = ["code" => 202, "msg" => "Table does't exists"];
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
