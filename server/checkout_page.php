<?php

require_once('../database/database.php');
$db = db_connect();


if ($_SERVER['REQUEST_METHOD'] == "POST") { //Making sure data is submitted
    $orderID = $_COOKIE['order']; //Retrieving orderID from Session
    $cc_num = $_POST["cc"];
    $exp = $_POST["exp"];
    $cvv = $_POST["cvv"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $province = $_POST["province"];
    $postal = $_POST["postal"];

    /*$sql = "INSERT INTO payments (orderID, cc_number, expiration_date, cvv_number, billing_fullname, 
                                    billing_street, billing_city, billing_province, billing_postal, payment_date) 
                                    VALUES('$orderID', '$cc_num', '$exp', '$cvv', '$name', '$address', '$city', '$province', '$postal', NOW())";
                                    
    $result = mysqli_query($db, $sql);
    $id = mysqli_insert_id($db);
    */
    $orderType = $_POST['orderType'];
    $price = $_POST['price'];
    header("Location: ../pages/confirmation.php?order=$orderID&type=$orderType&total=$price");
} else {
    header("Location: ../pages/index.php");
}


