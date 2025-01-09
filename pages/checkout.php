<?php 
// Written by Libert
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | Pizza Shop</title>
    <link rel="stylesheet" type="text/css" href="../css/checkout.css">
    <script src="../scripts/checkout.js" defer></script>
    <script src="../scripts/cart.js" defer></script>
</head>

<body>

    <?php include 'header.php'; ?> 

  <?php
   if (!isset($_COOKIE['order'])) {
    # If the user doesn't have an order, there's nothing to check out! Redirect them to the menu.
    Header("Location: ../pages/menu.php");
    }

    require_once("../database/database.php");
    $db = db_connect();

    # Grab the order ID from the cookie.
    $orderID = $_COOKIE['order'];

    # Use the order ID found in the cookie to get the relevant order details.
    $stmt = $db->prepare("SELECT delivery, total_price FROM orders WHERE orderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    # 0 (false) is takeout, 1 (true) is delivery
    $orderType = $order['delivery'] ? 'DELIVERY' : 'TAKEOUT';
    $price = $order['total_price'];
    $stmt->close();

    # Iterate over all the items inside of the order and create the relevant elements.
  function checkoutMaker() {
    $orderID = $_COOKIE['order'];
    global $db;
    $stmt = $db->prepare("SELECT menuItemID as itemID, qty FROM order_line WHERE orderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows
    $stmt->close();

    foreach($rows as $resultItem) {
        $itemID = $resultItem['itemID'];
        $qty = $resultItem['qty'];
        
        # Get the item details from the database.
        $stmt = $db->prepare("SELECT name, price, image FROM menu_items WHERE itemID = ?");
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
        
        # Extract the item details.
        $name = $item['name'];
        $price = $item['price'];
        $img = $item['image'];
        
        # Reuse custom JS WebComponent to create the checkout item.
        echo "<cart-item checkout='true' id='order-item-$itemID' name='$name' price='$price' qty='$qty' img='$img'></cart-item>";
    }
}
?>
    <main class="checkout">
        <form class="form-wrapper" action = "../server/checkout_page.php" method ="POST" onsubmit="return validate()">
            <h1>PAYMENT TITLE</h1>
            <div class="payment form">
                <div class="payment form-item long">
                    <label for="cc">Credit Card Number</label>
                    <input type="text" id="cc" name="cc" placeholder="XXXX XXXX XXXX XXXX" onblur="validateCreditCardField(this)" onfocus="onFocused(this)" required>
                </div>
                <div class="payment form-item short">
                    <label for="exp">Expiry Date</label>
                    <input type="text" id="exp" name="exp" placeholder="MM / YY" onblur="validateExpirationDateField(this)" onfocus="onFocused(this)" required>
                </div>
                <div class="payment form-item">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="XXX" onblur="validateCVVField(this)" onfocus="onFocused(this)" required>
                </div>
            </div>
            <div class="billing-address">
                <h2>Billing Address</h2>

                <div class="billing-prompt">
                    <input onclick="whenClicked()" type="checkbox" id="billing-checkbox" name="billing-checkbox">
                    <label for="billing-prompt">Billing address is different from delivery address</label>
                </div>

                <div class="billing form disabled">
                    <div class="billing form-item long">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Your Name" onblur="validateNameField(this)" onfocus="onFocused(this)">
                    </div>
                    <div class="billing form-item long">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="1234 Main St" onblur="validateAddressField(this)" onfocus="onFocused(this)">
                    </div>
                    <div class="billing form-item">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" placeholder="City"onblur="validateCityField(this)" onfocus="onFocused(this)">
                    </div>
                    <div class="billing form-item">
                        <label for="province">Province</label>
                        <input type="text" id="province" name="province" placeholder="Province" onblur="validateProvinceField(this)" onfocus="onFocused(this)">
                    </div>
                    <div class="billing form-item">
                        <label for="postal">Postal Code</label>
                        <input type="text" id="postal" name="postal" placeholder="A1A 1A1" onblur="validatePostalField(this)" onfocus="onFocused(this)">
                    </div>
                </div>
            </div>
            <input type="hidden" name="orderID" value="<?php echo $orderID ?>">
            <input type="hidden" name="orderType" value="<?php echo $orderType ?>">
            <input type="hidden" name="price" value="<?php echo $price ?>">
            <button type="submit" class="order-button">PLACE ORDER</button>
        </form>

        <div class="order">
            <div class="order-items">
                <!-- This calls the previously defined checkoutMaker. -->
                <?php echo checkoutMaker(); ?>
            </div>
            <div class="order-summary">
                <h2 class="order-summary-title">ORDER DETAILS</h2>
                <div class="order-summary-line">
                    <h3>TYPE</h3>
                    <p><?php echo $orderType ?></p>
                </div>
                <div class="order-summary-line">
                    <h3>ETA</h3>
                    <!-- Pick random ETA time since we aren't a real pizza shop. -->
                    <p><?php echo date('H:i', strtotime('+10 minutes'));?></p>
                </div>
                <div class="order-summary-line order-total">
                    <h2>TOTAL</h2>
                    <h2>$<?php echo $price ?></h2>
                </div>
            </div>
        </div>

    </main>
    <?php include 'footer.php'; ?>
</body>

</html>