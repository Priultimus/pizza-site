<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout | Pizza Shop</title>
    <link rel="stylesheet" type="text/css" href="../css/checkout.css">
    <script src="../scripts/checkout.js" defer></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="order">
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
            <input type="submit" class="order-button" value="PLACE ORDER">
        </form>

        <div class="order-info">
            <div class="order-items">

                <div class="order-item">
                    <img src="../images/placeholder_image.png" alt="ITEM ALT" />
                    <div class="order-item-info">
                        <h3>ITEM NAME</h3>
                        <p>$XX</p>
                        <p>QTY: X</p>
                    </div>
                </div>

                <div class="order-item">
                    <img src="../images/placeholder_image.png" alt="ITEM ALT" />
                    <div class="order-item-info">
                        <h3>ITEM NAME</h3>
                        <p>$XX</p>
                        <p>QTY: X</p>
                    </div>
                </div>

                <div class="order-item">
                    <img src="../images/placeholder_image.png" alt="ITEM ALT" />
                    <div class="order-item-info">
                        <h3>ITEM NAME</h3>
                        <p>$XX</p>
                        <p>QTY: X</p>
                    </div>
                </div>

                <div class="order-item">
                    <img src="../images/placeholder_image.png" alt="ITEM ALT" />
                    <div class="order-item-info">
                        <h3>ITEM NAME</h3>
                        <p>$XX</p>
                        <p>QTY: X</p>
                    </div>
                </div>

                <div class="order-item">
                    <img src="../images/placeholder_image.png" alt="ITEM ALT" />
                    <div class="order-item-info">
                        <h3>ITEM NAME</h3>
                        <p>$XX</p>
                        <p>QTY: X</p>
                    </div>
                </div>

            </div>
            <div class="order-details">
                <h2 class="order-details-title">ORDER DETAILS</h2>
                <div class="order-details-line">
                    <h3>TYPE</h3>
                    <p>DELIVERY</p>
                </div>
                <div class="order-details-line">
                    <h3>ETA</h3>
                    <p>XX:XX</p>
                </div>
                <div class="order-details-line total">
                    <h2>TOTAL</h2>
                    <h2>$XX</h2>
                </div>
            </div>
        </div>

    </main>
    <?php include 'footer.php'; ?>
</body>

</html>