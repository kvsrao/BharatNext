<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include './DbConnection.php';
$db = new Db();
$retres = array();
$department = "";
$openingdate = "";
$closingdate = "";
$jobtitle = "";
$vacancies = "";
$agegeneral = "";
$agebcobc = "";
$agesc = "";
$qualificationfirst = "";
$qualificationsecond = "";
$qualificationthired = "";
//$howtoapply = "";
$declaration = "";
$eligibility = "";
$qualification = "";
$reservations = "";
$restolocal = "";
$defoflocal = "";
$age = "";
$dhowtoapply = "";
$fee = "";
$modeofpayment = "";
$schemeofexam = "";
$centers = "";



extract($_POST);

if (isset($_POST)) {
    if ($db::$dbcon) {
        $dbrest = null;
        if (!isset($slno) and empty($slno)) {
            $dbrest = $db->query("insert into notifications(department,openingdate,closingdate,jobtitle,vacancies,agegeneral,agebcobc,agesc,qualificationfirst,qualificationsecond,qualificationthired,howtoapply,declaration,eligibility,qualification,reservations,restolocal,defoflocal,age,fee,modeofpayment,schemeofexam,centers)"
                    . " values(:department,:openingdate,:closingdate,:jobtitle,:vacancies,:agegeneral,:agebcobc,:agesc,:qualificationfirst,:qualificationsecond,:qualificationthired,:dhowtoapply,:declaration,:eligibility,:qualification,:reservations,:restolocal,:defoflocal,:age,:fee,:modeofpayment,:schemeofexam,:centers)", 
                    [":department"=>$department,":openingdate"=>$openingdate,":closingdate"=>$closingdate,":jobtitle"=>$jobtitle,":vacancies"=>$vacancies,":agegeneral"=>$agegeneral,":agebcobc"=>$agebcobc,":agesc"=>$agesc,":qualificationfirst"=>$qualificationfirst,":qualificationsecond"=>$qualificationsecond,":qualificationthired"=>$qualificationthired,":dhowtoapply"=>$dhowtoapply,":declaration"=>$declaration,":eligibility"=>$eligibility,":qualification"=>$qualification,":reservations"=>$reservations,":restolocal"=>$restolocal,":defoflocal"=>$defoflocal,"age"=>$age,":fee"=>$fee,":modeofpayment"=>$modeofpayment,":schemeofexam"=>$schemeofexam,":centers"=>$centers]); //->get();
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
