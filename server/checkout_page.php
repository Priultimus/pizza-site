<!-- Code Developed by Gabe -->

<?php
// Establish a connection to the database by including the database connection file.
require_once('../database/database.php');
$db = db_connect(); // Call the function to connect to the database.

// Check if the form has been submitted via POST method (i.e., payment form).
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    // Retrieve the order ID from the session
    $orderID = $_SESSION['orderID'];
    
    // Retrieve user input from the submitted form (credit card info and billing address).
    $cc_num = $_POST["cc"]; // Credit card number.
    $exp = $_POST["exp"]; // Expiration date of the card.
    $cvv = $_POST["cvv"]; // CVV number on the card.
    $name = $_POST["name"]; // Full name of the person making the payment.
    $address = $_POST["address"]; // Billing address.
    $city = $_POST["city"]; // Billing city.
    $province = $_POST["province"]; // Billing province or state.
    $postal = $_POST["postal"]; // Billing postal or zip code.
    
    // SQL query to insert the payment details into the 'payments' table.
    $sql = "INSERT INTO payments (orderID, cc_number, expiration_date, cvv_number, billing_fullname, 
                                    billing_street, billing_city, billing_province, billing_postal, payment_date) 
                                    VALUES('$orderID', '$cc_num', '$exp', '$cvv', '$name', '$address', '$city', '$province', '$postal', NOW())";
    
    // Execute the SQL query to insert the payment data into the database.
    $result = mysqli_query($db, $sql);
    
    // Retrieve the last inserted payment ID.
    $id = mysqli_insert_id($db);
    
    // Redirect the user to the landing page.
    header("Location: ../pages/index.php");
} else {
    // If the form is not submitted via POST, redirect the user back to the checkout page.
    header("Location: ../checkout.php");
}
?>


