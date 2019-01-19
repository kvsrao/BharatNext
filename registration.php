<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include './DbConnection.php';
$db = new Db();
$retres = array();
extract($_POST);

if (isset($_POST) && !is_null($name) && !is_null($email_id) && !is_null($mobile_no) && !is_null($password) && !empty($firebaseid)  && !is_null($firebaseid) ) {
    if ($db::$dbcon) {
        $dbrest = null;
        if (!isset($slno) and empty($slno)) {
            $dbtest = $db->query("select * from users where email = :email and mobile = :mobile", [":email" => $email_id, "mobile" => $mobile_no]);
            if ($dbtest->result->rowCount() == 0) {
                $dbrest = $db->query("insert into users(name,email,mobile,password,firebaseid) values(:name,:email,:mobile,:password,:firebaseid)", [':firebaseid'=>$firebaseid, ':name' => $name, ':email' => $email_id, ':mobile' => $mobile_no, ':password' => $password]); //->get();
            } else {
                array_push($retres, ["code" => 201, "mesg" => "Already Registered"]);
            }
        } else {
            $dbrest = $db->query("update users  set name=:name,email=:email,mobile=:mobile,password=:password,firebaseid =:firebaseid where slno = :slno", [':firebaseid'=>$firebaseid, ':name' => $name, ':email' => $email_id, ':mobile' => $mobile_no, ':password' => $password, ":slno" => $slno]); //->get();
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
