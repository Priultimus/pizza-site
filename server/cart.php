<?php

session_start();

require_once('../database/database.php');
$db = db_connect();

// Check if we're receiving a GET request.
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $loginID = $_SESSION['loginID'];

    // Retrieve the cart info based on the session's loginID.
    $stmt = $db->prepare("SELECT cart_items FROM cart WHERE loginID = ?");
    $stmt->bind_param("i", $loginID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Send the cart to the client.
    echo json_encode($result->fetch_assoc()['cart_items']);
    exit();
}
else if ($_SERVER['REQUEST_METHOD'] == 'POST'){ // Handle POST requests.
    // Get the cart and request data.
    $loginID = $_SESSION['loginID'];
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // If the request data contains a "cartJson".
    if (isset($data['cartJson']))
    {
        // Encode the JSON as a precaution.
        $cartJson = json_encode($data['cartJson']);

        // Update the database with the new JSON.
        $stmt = $db->prepare("UPDATE cart SET cart_items = ? WHERE loginID = ?");
        $stmt->bind_param("si", $cartJson, $loginID);
        $stmt->execute();

        // Send the new cart back to the client.
        header('Content-Type: application/json');
        echo json_encode([
            "cartJson" => $cartJson
        ]);

        exit();
    }
}
?>
