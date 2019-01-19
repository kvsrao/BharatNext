<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
extract($_POST);

if (isset($_POST) && !is_null($email_id) && !is_null($password)) {

    if ($db::$dbcon) {
        $dbrest = $db->query("select * from users where email = :email ", [':email' => $email_id]); //->get();
        if ($dbrest) {
            if ($dbrest->result->rowCount() > 0) {
                $dbupdate = $db->query("update users  set password=:password where email = :email ", [':email' => $email_id, ':password' => $password]);
                if ($dbupdate->result->rowCount() > 0) {
                    array_push($retres, ["code" => 200, "mesg" => "Your Password has been updated successfully"]);
                } else {
                    array_push($retres, ["code" => 205, "mesg" => "Password Update Fails"]);
                }
            } else {
                array_push($retres, ["code" => 201, "mesg" => "Verification Fails, Not Registered"]);
            }
        } else {
            array_push($retres, ["code" => 202, "mesg" => "Table does't exists"]);
        }
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
