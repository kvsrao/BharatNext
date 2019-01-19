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
extract($_GET);

$pslno = "";

if (isset($_GET) && !is_null($slno)) {

    if ($db::$dbcon) {
        $pdbrequest = $db->query("select p.pslno  from policyreg p, users u where u.mobile = p.mobile and p.email = u.email and u.slno = :slno ", [":slno" => $slno]);
        if ($pdbrequest->result->rowCount() > 0) {
            $pslno = $pdbrequest->get()[0]->pslno;
        }

        $dbrest = $db->query("select policyid,category from policies"); //->get();
        if ($dbrest) {
            if ($dbrest->result->rowCount() > 0) {
                $results = $dbrest->get();
                foreach ($results as $result) {
                    $res[] = ["policyid" => $result->policyid, "category" => $result->category, "status" => TRUE, "pslno" => $pslno];
                }
                $retres = ["policies" => $res];
            }
        }
    }
}

echo json_encode($retres);

function clean($string) {
    $string = str_replace(" ", "-", $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
