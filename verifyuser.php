<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
extract($_POST);

if (isset($_POST) && !is_null($userid)) {

    if ($db::$dbcon) {
        $dbrest = $db->query("select * from users where email = :userid or mobile = :userid ", [':userid' => $userid]); //->get();
        if ($dbrest) {
            if ($dbrest->result->rowCount() > 0) {
                array_push($retres, ["code" => 200, "mesg" => "Verified success"]);
            } else {
                array_push($retres, ["code" => 201, "mesg" => "Verification Fails"]);
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
