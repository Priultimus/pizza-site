<?php 
unset($_COOKIE['order']);
setcookie('order', '', -1, '/');
if (array_key_exists('order', $_GET)) {
    $orderID = $_GET['order'];
    $orderType = $_GET['type'];
    $total = $_GET['total'];
} else (
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