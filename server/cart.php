<?php
// Written by Libert
session_start();
require_once('../database/database.php');
$db = db_connect();
Header("Content-Type: application/json"); // Only going to be responding in JSON format.

// There are different tables for temporary users versus logged in users, so we have multiple statements here.
if (isset($_SESSION['loginID'])) {
    $userID = $_SESSION['loginID'];
    $getStatement = "SELECT loginID, cartItems FROM cart WHERE loginID = ?";
    $postStatement = "INSERT INTO cart (loginID, cartItems) VALUES (?, ?) ON DUPLICATE KEY UPDATE cartItems = VALUES(cartItems)";
    $deleteStatement = "DELETE FROM cart WHERE loginID = ?";

} else if (isset($_SESSION['tempID'])) {
    $userID = $_SESSION['tempID'];
    $getStatement = "SELECT tempID, tempcart as cartItems FROM tempLogins WHERE tempID = ?";
    $postStatement = "INSERT INTO tempLogins (tempID, tempcart) VALUES (?, ?) ON DUPLICATE KEY UPDATE tempcart = VALUES(tempcart)";
    $deleteStatement = "DELETE FROM tempLogins WHERE tempID = ?";

} else {
    // If the user is not logged in.
    Header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "You must be logged in to access this page. "]);
    exit();
}

// Responding to cart retrival request.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $cart = array("cart_items" => []);
    // Retrieve the cart info based on the session's loginID.
    $stmt = $db->prepare($getStatement);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        Header("HTTP/1.1 500 Internal Server Error");
        $error = $stmt->error;
        echo json_encode(["error" => "Failed to retrieve cart. ", "details" => $error]);
        exit();
    }
    $data = $result->fetch_assoc();
    $stmt->close();

    if (array_key_exists('checkout', $_GET) && !empty($data)) {
        // If the user is checking out, clear the cart.
        
        if (!isset($_SESSION['loginID'])) {
            Header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["error" => "You must be logged in to checkout. "]);
            exit();
        }
        $stmt = $db->prepare($deleteStatement);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
        
        $orderID = $_GET['checkout'];
        $cartItems = json_decode($data['cartItems'], true);
        foreach($cartItems as $item) {
            $itemId = $item['itemId'];
            $qty = $item['qty'];
            $stmt = $db->prepare("INSERT INTO order_line (orderID, menuItemID, qty) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $orderID, $itemId, $qty);
            $stmt->execute();
            $stmt->close();
        }
        if (array_key_exists('user', $_GET)) {
            $headerString = "Location: ../pages/checkout.php?orderid=$orderID";
            Header('');
            exit();
        }
        echo json_encode(["success" => "cart cleared ", "order_line" => $cartItems]);
        exit();
    }

    if (array_key_exists('saveCart', $_GET)) {
        // If the user is saving the cart.
        if (!isset($_SESSION['loginID']) && !(isset($_SESSION['tempID']) || isset($_SESSION['oldTempID']))) {
            Header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["error" => "You must be logged in to save your cart. "]);
            exit();
        }
        $stmt = $db->prepare('SELECT tempcart as cartItems FROM templogins WHERE tempID = ?');
        if (isset($_SESSION['oldTempID'])) {
            $stmt->bind_param("i", $_SESSION['oldTempID']);
        } else {
            $stmt->bind_param("i", $_SESSION['tempID']);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        $stmt = $db->prepare($postStatement);
        $stmt->bind_param("is", $userID, $data['cartItems']);
        $stmt->execute();
        $stmt->close();
        echo json_encode(["success" => "cart saved "]);
        exit();
    }

    if (!empty($data)) {
        // Send the cart to the client.
        $cart['cart_items'] = json_decode($data['cartItems'], true);
    }
    echo json_encode($cart);
    exit();

} else if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle POST requests.
    // Get the cart and request data.
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // If the request data contains cart items
    if (isset($data['cart_items'])) {
        // Encode the JSON as a precaution.
        $cartItems = json_encode($data['cart_items']);

        // Update the database with the new JSON.
        $stmt = $db->prepare($postStatement);
        $stmt->bind_param("is", $userID, $cartItems);
        $stmt->execute();

        // Send the new cart back to the client.
        echo json_encode([
                "cart_items" => $cartItems
            ]);

        exit();
    } else {
        // If the request data does not contain cart items.
        Header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "Invalid request data. "]);
        exit();
    }
}

?>
