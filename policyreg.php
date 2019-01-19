<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include './DbConnection.php';
include './vendor/autoload.php';
include './getBearerToken.php';

use Firebase\JWT\JWT;

$db = new Db();
$retres = array();

$name = "";
$mobile = "";
$referred_id = "";
$dob = "";
$cast = "";
$state = "";
$city = "";
$ward = "";
$gender = "";
$doorno = "";
$streetname = "";
$locality = "";
$pin = "";
$qualification = "";
$subcity = "";
$email = "";
$relation = "";
$pref_category = "";
$delete = false;


$retres = array();
extract($_POST);
$dbrest = null;

$token = "";

$token = getBearerToken();


if (isset($_POST) && !empty($token)) {

    $dtoken = JWT::decode($token, KEY, array("HS256"));
    $dslno = $dtoken->slno;
    $expire = $dtoken->expire;
    $time = time();
    if ($expire > $time) {
        if ($db::$dbcon) {
            if (!isset($pslno) and empty($pslno)) {
//            $dtoken = JWT::decode($token, KEY, array("HS256"));
//            $dslno = $dtoken->slno;
                $dbtest = $db->query("select * from policyreg where mobile = :mobile", [":mobile" => $mobile]);
                if ($dbtest->result->rowCount() == 0) {
                    $dbrest = $db->query("insert into policyreg(referred_id,name,mobile,email,dob,cast,state,city,ward,gender,doorno,streetname,locality,pin,qualification,subcity,relation,pref_category) values(:referred_id,:name,:mobile,:email,:dob,:cast,:state,:city,:ward,:gender,:doorno,:streetname,:locality,:pin,:qualification,:subcity,:relation,:pref_category)", [":email" => $email, ":relation" => $relation, ":pref_category" => $pref_category, ":referred_id" => $referred_id, "name" => $name, "mobile" => $mobile, ":dob" => $dob, ":cast" => $cast, ":state" => $state, ":city" => $city, ":ward" => $ward, ":gender" => $gender, ":doorno" => $doorno, ":streetname" => $streetname, ":locality" => $locality, ":pin" => $pin, ":qualification" => $qualification, ":subcity" => $subcity]); //->get();
                } else {
                    $retres = ["code" => 201, "mesg" => "Mobile Already Registered"];
                }
            } else {
                if (!empty($pslno) && (!$delete)) {
                    $dbrest = $db->query("update policyreg  set  email=:email, relation=:relation, pref_category=:pref_category, referred_id=:referred_id,name=:name,mobile=:mobile,dob=:dob,cast=:cast,state=:state,city=:city,ward=:ward,gender=:gender,doorno=:doorno,streetname=:streetname,locality=:locality,pin=:pin,qualification=:qualification,subcity=:subcity where pslno=:pslno", [":email" => $email, ":pref_category" => $pref_category, ":relation" => $relation, "pslno" => $pslno, ":referred_id" => $referred_id, "name" => $name, "mobile" => $mobile, ":dob" => $dob, ":cast" => $cast, ":state" => $state, ":city" => $city, ":ward" => $ward, ":gender" => $gender, ":doorno" => $doorno, ":streetname" => $streetname, ":locality" => $locality, ":pin" => $pin, ":qualification" => $qualification, ":subcity" => $subcity]); //->get();
                } elseif (!empty($pslno) && ($delete)) {
                    $dbrest = $db->query("delete from policyreg where pslno = :pslno", [":pslno" => $pslno]);
                } else {
                    $retres = ["code" => 205, "mesg" => "Update Fails, Not Matching Ids"];
                }
            }

            if ($dbrest) {
                if ($dbrest->result->rowCount() > 0) {
                    if (!isset($pslno) and empty($pslno) && !$delete) {
                        $retres = ["code" => 200, "mesg" => "Successfully Registered", "pslno" => $db::$dbcon->lastInsertId()];
                    } elseif (!empty($pslno) && ($delete)) {
                        $retres = ["code" => 201, "mesg" => "Successfully Deleted"];
                    } else {
                        $retres = ["code" => 202, "mesg" => "Successfully Updated"];
                    }
                } else {
                    $retres = ["code" => 203, "mesg" => "Some Thing Went Wrong"];
                }
            }
//        else {
//            array_push($retres, ["code" => 202, "mesg" => "Some Thing Went Wrong"]);
//        }
        } else {
            $retres = ["code" => 206, "mesg" => "wrong db credentials"];
        }
    } else {
        $retres = ["code" => 205, "mesg" => "Your Session Hasbeen Expired"];
    }
} else {
    $retres = ["code" => 207, "mesg" => "values should not be is_null"];
}
echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
