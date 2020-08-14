<?php

require 'db.php';

session_start();

$username = $_POST['username'];

$tm = date("Y-m-d H:i:s");
$sql = $mysqli->query("UPDATE activestatus SET astatus= '1', tm = '$tm' where username = '$username'");

$gap = 100; //Gap in seconds
$tLeft = date("Y-m-d H:i:s", mktime (date("H"),date("i"),date("s")-$gap,date("m"),date("d"),date("Y")));

$ut = $mysqli->query("UPDATE activestatus SET astatus = '0' where tm < '$tLeft'");

?>