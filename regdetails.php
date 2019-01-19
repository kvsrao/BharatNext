<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
error_reporting(E_ERROR | E_PARSE);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include './DbConnection.php';
include './vendor/autoload.php';
include './getBearerToken.php';

use Firebase\JWT\JWT;

$db = new Db();
$retres = array();
extract($_POST);
$name = "";
$email = "";
$mobile = "";
$dob = "";
$cast = "";
$state = "";
$city = "";
$ward = "";
$gender = "";
$mstatus = "";
$doorno = "";
$streetname = "";
$locality = "";
$pin = "";
$qualification = "";
$course = "";
$specialisation = "";
$subcity = "";
$t10th = "";
$t12th = "";
$diploma = "";


$retres = array();
extract($_POST);
$dbrest = null;
$token = getBearerToken();



if (isset($_POST) && !is_null($token)) {
    if ($db::$dbcon) {
        $dtoken = JWT::decode($token, KEY, array("HS256"));
        $dslno = $dtoken->slno;
        $expire = $dtoken->expire;
        $time = time();
        if ($expire > $time) {
            if (!isset($slno) and empty($slno)) {
                $dbtest = $db->query("select * from registrations where userid = :slno", [":slno" => $dslno]);
                if ($dbtest->result->rowCount() == 0) {
                    $dbrest = $db->query("insert into registrations(userid,dob,cast,state,city,ward,gender,mstatus,doorno,streetname,locality,pin,qualification,course,specialisation,subcity,t10th,t12th,diploma) values(:userid,:dob,:cast,:state,:city,:ward,:gender,:mstatus,:doorno,:streetname,:locality,:pin,:qualification,:course,:specialisation,:subcity,:10th,:12th,:diploma)", [":userid" => $dslno, ":dob" => $dob, ":cast" => $cast, ":state" => $state, ":city" => $city, ":ward" => $ward, ":gender" => $gender, ":mstatus" => $mstatus, ":doorno" => $doorno, ":streetname" => $streetname, ":locality" => $locality, ":pin" => $pin, ":qualification" => $qualification, ":course" => $course, ":specialisation" => $specialisation, ":subcity" => $subcity, ":10th" => $t10th, ":12th" => $t12th, ":diploma" => $diploma]); //->get();
                } else {
                    array_push($retres, ["code" => 201, "mesg" => "Already Registered"]);
                }
            } else {
                if ($dslno == $slno) {
                    $dbrest = $db->query("update registrations  set dob=:dob,cast=:cast,state=:state,city=:city,ward=:ward,gender=:gender,mstatus=:mstatus,doorno=:doorno,streetname=:streetname,locality=:locality,pin=:pin,qualification=:qualification,course=:course,specialisation=:specialisation,subcity=:subcity,t10th=:10th,t12th=:12th,diploma=:diploma where userid=:slno", [":slno" => $slno, ":dob" => $dob, ":cast" => $cast, ":state" => $state, ":city" => $city, ":ward" => $ward, ":gender" => $gender, ":mstatus" => $mstatus, ":doorno" => $doorno, ":streetname" => $streetname, ":locality" => $locality, ":pin" => $pin, ":qualification" => $qualification, ":course" => $course, ":specialisation" => $specialisation, ":subcity" => $subcity, ":10th" => $t10th, ":12th" => $t12th, ":diploma" => $diploma]); //->get();
                } else {
                    array_push($retres, ["code" => 205, "mesg" => "Update Fails, Not Matching Ids"]);
                }
            }
            if ($dbrest) {
                if ($dbrest->result->rowCount() > 0) {
                    if (!isset($slno) and empty($slno)) {
                        array_push($retres, ["code" => 200, "mesg" => "Successfully Registered"]);
                    } else {
                        array_push($retres, ["code" => 200, "mesg" => "Successfully Updated"]);
                    }
                } else {
                    array_push($retres, ["code" => 203, "mesg" => "Some Thing Went Wrong"]);
                }
            }
//        else {
//            array_push($retres, ["code" => 202, "mesg" => "Some Thing Went Wrong"]);
//        }
        } else {
            array_push($retres, ["code" => 205, "mesg" => "Your Session Hasbeen Expired"]);
        }
    } else {
        array_push($retres, ["code" => 204, "mesg" => "wrong db credentials"]);
    }
} else {
    array_push($retres, ["code" => 203, "mesg" => "Token Missing"]);
}
echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
