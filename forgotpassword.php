<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include './DbConnection.php';
$db = new Db();
$retres = array();
extract($_POST);

if (isset($_POST) && !is_null($email_id)) {

    if ($db::$dbcon) {
        $dbrest = $db->query("select * from users where email = :email", [':email' => $email_id]); //->get();
        if ($dbrest) {
            if ($dbrest->result->rowCount() > 0) {
                $otp = generateOTP();
                $mailstatus = mail($email_id, "otp", "OTP For the password reset :" . $otp);
                array_push($retres, ["code" => 200, "mesg" => "OTP Has been successfully sent to your registered email_id", "otp" => $otp]);
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

function generateOTP($length = 4, $level = 1) {

    $validchars[1] = "0123456789";
    $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

    $password = "";
    $counter = 0;

    while ($counter < $length) {
        $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
        // All character must be different
        if (!strstr($password, $actChar)) {
            $password .= $actChar;
            $counter++;
        }
    }

    return $password;
}
