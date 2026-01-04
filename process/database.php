<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "db_ticket_bts";

$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if (!$con) {
    die("Database gagal terhubung");
}
mysqli_set_charset($con, "utf8");
date_default_timezone_set("Asia/Jakarta");
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
