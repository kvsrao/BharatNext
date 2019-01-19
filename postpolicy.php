<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include './DbConnection.php';
$db = new Db();
$retres = array();

$name = "";
$pfrom = "";
$category = "";
$age = "";
$gender = "";
$cast = "";
$state = "";
$district = "";
$sub_district = "";
$ward = "";
$details = "";
$whom = "";

extract($_POST);

if (isset($_POST)) {
    if ($db::$dbcon) {
        $dbrest = null;
        if (!isset($slno) and empty($slno)) {
            $dbrest = $db->query("insert into policies(name,pfrom,category,age,gender,cast,state,district,sub_district,ward,details,whom)"
                    . " values(:name,:pfrom,:category,:age,:gender,:cast,:state,:district,:sub_district,:ward,:details,:whom)", [ "whom"=>$whom,":name"=>$name,":pfrom"=>$pfrom,":category"=>$category,":age"=>$age,":gender"=>$gender,":cast"=>$cast,":state"=>$state,":district"=>$district,":sub_district"=>$sub_district,":ward"=>$ward,":details"=>$details]); //->get();
        } else {
//            $dbrest = $db->query("update users  set name=:name,email=:email,mobile=:mobile,password=:password where slno = :slno", [':name' => $name, ':email' => $email_id, ':mobile' => $mobile_no, ':password' => $password, ":slno" => $slno]); //->get();
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
        array_push($retres, ["code" => 204, "mesg" => "wrong db credentials"]);
    }
} else {
    array_push($retres, ["code" => 203, "mesg" => "values should not be is_null"]);
}
echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
