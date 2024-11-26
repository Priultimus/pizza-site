<?php

session_start();
require_once('../database/database.php');
$db = db_connect();
Header("Content-Type: application/json");

if (isset($_SESSION['loginID'])) {
    $userID = $_SESSION['loginID'];
    $getStatement = "SELECT * FROM orders WHERE loginID = ?";
    $deliveryPostStatement = "INSERT INTO orders (loginID, delivery, street, city, province, postal) VALUES (?, 1, ?, ?, ?, ?)";
    $takeoutPostStatement = "INSERT INTO orders (loginID) VALUES (?)";

} else if (isset($_SESSION['tempID'])) {
    $userID = $_SESSION['tempID'];
    $getStatement = "SELECT * FROM orders WHERE tempID = ?";
    $deliveryPostStatement = "INSERT INTO orders (tempID, delivery, street, city, province, postal) VALUES (?, 1, ?, ?, ?, ?)";
    $takeoutPostStatement = "INSERT INTO orders (tempID) VALUES (?)";
} else {
    Header("HTTP/1.1 401 Unauthorized");
    echo json_encode(["error" => "You must be logged in to access this page. "]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (array_key_exists('completeOrder', $_GET)) {
        $orderID = $_GET['completeOrder'];
        $stmt = $db->prepare("SELECT menuItemID, qty FROM order_line WHERE orderID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $items = $stmt->get_result();
        $stmt->close();
        $total = 0;
        while ($item = $items->fetch_assoc()) {
            $stmt = $db->prepare("SELECT price FROM menu_items WHERE menuItemID = ?");
            $stmt->bind_param("i", $item['menuItemID']);
            $stmt->execute();
            $price = $stmt->get_result()->fetch_assoc();
            $total += $price['price'] * $item['qty'];
        }
        $stmt = $db->prepare("UPDATE orders SET completed = 1, price = ? WHERE orderID = ?");
        $stmt->bind_param("ii", $orderID, $total);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        echo json_encode($order);
        exit();

    } else if (array_key_exists('saveOrder', $_GET)) {
        $orderID = $_GET['saveOrder'];
        if (!isset($_SESSION['loginID']) && !isset($_SESSION['tempID'])) {
            Header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["error" => "You must be logged in to save orders. "]);
            exit();
        }
        $loginID = $_SESSION['loginID'];;
        $stmt = $db->prepare("UPDATE orders SET tempID = null, loginID = ? WHERE orderID = ?");
        $stmt->bind_param("ii", $loginID, $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        echo json_encode($order);
        exit();
    } else {
        $orders = array("orders" => []);
        $stmt = $db->prepare($getStatement);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            Header("HTTP/1.1 500 Internal Server Error");
            $error = $stmt->error;
            echo json_encode(["error" => "Failed to retrieve orders. ", "details" => $error]);
            exit();
        }
        while ($data = $result->fetch_assoc()) {
            $orders['orders'][] = $data;
        }
        echo json_encode($orders);
        exit();
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    if(array_key_exists('order-type', $_POST)) {
    $orderType = $_POST['order-type']; 
    if ($orderType == 'delivery') {
        $address = $_POST['address-data'];
        $address = json_decode($address, true);
        $stmt = $db->prepare($deliveryPostStatement);
        $stmt->bind_param("issss", $userID, $address['street'], $address['city'], $address['province'], $address['postal_code']);
    } else {
        $stmt = $db->prepare($takeoutPostStatement);
        $stmt->bind_param("i", $userID);
    }
    $stmt->execute();
    $orderID = $stmt->insert_id;
    $_SESSION['orderState'] = $orderID;
    if (array_key_exists('loginID', $_SESSION)) {
        $loginID = $_SESSION['loginID'];
        $stmt = $db->prepare("SELECT cartItems FROM cart WHERE loginID = ?");
        $stmt->bind_param("i", $loginID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if (array_key_exists('cartItems', $data)) {
            header("Location: ../server/cart.php?checkout=$orderID&user=true");
            exit();
        }
    }
    Header("Location: ../pages/menu.php");
    exit();
}
    // If the request data contains order information 
    if (isset($data['order'])) {
        $orderDetails = json_encode($data['order']);
        if (isset(orderDetails['delivery'])) {
            $stmt = $db->prepare($deliveryPostStatement);
            $stmt->bind_param("issss", $userID, $orderDetails['street'], $orderDetails['city'], $orderDetails['province'], $orderDetails['postal']);
        } else {
            $stmt = $db->prepare($takeoutPostStatement);
            $stmt->bind_param("i", $userID);
        }
        // Insert the order into the database
        $stmt->execute();
        $orderID = $stmt->insert_id;
        $_SESSION['orderState'] = $orderID;
        echo json_encode(["success" => "order started", "id" => $orderID]);
        exit();
        
    }
}