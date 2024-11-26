<?php

session_start();

require_once('../database/database.php');
$db = db_connect();

$loginID = $_SESSION['loginID'];

$stmt = $db->prepare("SELECT cart_items FROM cart WHERE loginID = ?");
$stmt->bind_param("i", $loginID);
$stmt->execute();



if ($stmt->fetch()){
    echo $loginID;
} else {
    echo "No Cart Found";
}
