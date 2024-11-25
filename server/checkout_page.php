<?php

require_once('../database/database.php');
$db = db_connect();


if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Making sure data is submitted
    // $cc_num = $_POST["cc"];
    // $exp = $_POST["exp"];
    // $cvv = $_POST["cvv"];
    // $name = $_POST["name"];
    // $address = $_POST["address"];
    // $city = $_POST["city"];
    // $province = $_POST["province"];
    // $postal = $_POST["postal"];

    $sql = "INSERT INTO payments (orderID, cc_number, expiration_date, cvv_number, billing_fullname, 
                                    billing_street, billing_city, billing_province, billing_postal) 
                VALUES('1', '1234123412341234', '04/29', '129', 'gabe rai', '5 main st.', 'Ottawa', 'ON', 'A1A-1A1')";
                // -- VALUES('1', '$cc_num', '$exp', '$cvv', '$name', '$address', '$city', '$province', '$postal')";
                
    $result = mysqli_query($db, $sql);
    $id = mysqli_insert_id($db);
    
    header("Location: confirmation.php?id=$id");
} else {
    header("Location: ../checkout.html");
    
}
    

    

