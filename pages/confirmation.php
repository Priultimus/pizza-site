<?php 
// Written by Libert & Gabe

// The order cookie is unset to clear the order.
if (isset($_COOKIE['order'])) {
    unset($_COOKIE['order']);
    setcookie('order', '', -1, '/');
}

// The information about the order is fetched from the URL parameters.
if (array_key_exists('order', $_GET) && array_key_exists('type', $_GET) && array_key_exists('total', $_GET)) {
    $orderID = $_GET['order'];
    $orderType = $_GET['type'];
    $total = $_GET['total'];

} else (
    // If the URL parameters are not set, no use for this page, go back to landing.
    header("Location: ../pages/index.php")
)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Success | Pizza Shop</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="confirm-container">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order! Your order has been placed and is being prepared.</p>
        <p>Order Number: <?php echo $orderID ?></p>
        <p>Order Type: <?php echo $orderType ?></p>
        <p>Total: $<?php echo $total ?></p>
        <button onclick="window.location.href='../pages/index.php'">GO HOME</button>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>