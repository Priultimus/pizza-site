<?php require_once('./server/menu_items.php');

function menuMaker($category) { 
    $menuItems = fetchMenuItemsByCategory($category);
    foreach ($menuItems as $itemId => $item) {
        echo "
        <div class='menu-card-wrapper' id='$itemId'>
        $item";
        echo returnWholeReviewElement($itemId);
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu | Pizza Shop</title>
    <link rel="stylesheet" type="text/css" href="css/globals.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
    <link rel="stylesheet" type="text/css" href="css/reviews.css">
    <link rel="stylesheet" type="text/css" href="css/cart.css">
</head>

<body>

    <?php include 'pages/header.php'; ?>

    <main>
        <header class="menu-subheader">
            <div class="menu-options">
                <button class="menu-subheader-option selected">ALL</button>
                <button class="menu-subheader-option">PIZZAS</button>
                <button class="menu-subheader-option">PASTAS</button>
                <button class="menu-subheader-option">SALADS</button>
                <button class="menu-subheader-option">DESSERTS</button>
            </div>
            <form class="menu-search-wrapper" method="GET">
                <input class="menu-search" type="text" id="search" name="search"
                    placeholder="FIND YOUR PERFECT MEAL...">
                <button type="submit" class="menu-search-button">
                    <img src="images/search.svg" alt="Search Icon" />
                </button>
            </form>
        </header>
        <h1>OUR MENU</h1>
        <div class="menu">
            <div class="menu-cards">
                <?php echo menuMaker("all"); ?>

                <div class="menu-card-wrapper">
                    <div class="menu-card">
                        <div class="menu-card-panel">
                            <img class="menu-card-image" src="images/placeholder_image.png" alt="ITEM ALT" />
                            <div class="menu-card-details">
                                <h3>ITEM NAME</h3>
                                <p>ITEM DESCRIPTION</p>
                            </div>
                            <img class="menu-card-stars" src="images/fivestars.svg" alt="Five Stars" />
                        </div>
                        <button class="menu-card-button">
                            <img src="images/checkout.svg" alt="Cart Icon" />
                            <span>Add to Cart</span></button>
                    </div>
                </div>

                <div class="menu-card-wrapper">
                    <div class="menu-card">
                        <div class="menu-card-panel">
                            <img class="menu-card-image" src="images/placeholder_image.png" alt="ITEM ALT" />
                            <div class="menu-card-details">
                                <h3>ITEM NAME</h3>
                                <p>ITEM DESCRIPTION</p>
                            </div>
                            <img class="menu-card-stars" src="images/fivestars.svg" alt="Five Stars" />
                        </div>
                        <button class="menu-card-button">
                            <img src="images/checkout.svg" alt="Cart Icon" />
                            <span>Add to Cart</span></button>
                    </div>
                </div>
            </div>

            <div class="cart">
                <h2>YOUR CART</h2>
                <div class="cart-items">
                    <div class="cart-item">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="cart-detail">
                            <h3>ITEM NAME</h3>
                            <p>$XX</p>
                            <p>QTY: X</p>
                        </div>
                    </div>

                    <div class="cart-item">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="cart-detail">
                            <h3>ITEM NAME</h3>
                            <p>$XX</p>
                            <p>QTY: X</p>
                        </div>
                    </div>

                    <div class="cart-item">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="cart-detail">
                            <h3>ITEM NAME</h3>
                            <p>$XX</p>
                            <p>QTY: X</p>
                        </div>
                    </div>

                    <div class="cart-item">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="cart-detail">
                            <h3>ITEM NAME</h3>
                            <p>$XX</p>
                            <p>QTY: X</p>
                        </div>
                    </div>
                </div>
                <div class="cart-total">
                    <h2>TOTAL</h2>
                    <h2>$XX</h2>
                </div>
                <button class="cart-checkout">Checkout</button>
            </div>

        </div>
        
    </main>
    <?php include 'pages/footer.php'; ?>
    <script src="scripts/menu.js"></script>
</body>

</html>

<?php 
if (array_key_exists("item_id", $_GET) && itemIdExists($_GET["item_id"])) {
    $item_id = $_GET["item_id"];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                let elem = document.getElementById('$item_id');
                elem.classList.add('expanded');
                elem.scrollIntoView();
            });
        </script>";
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && array_key_exists("your-name", $_POST)) {
    $itemId = $_POST['item-id'];
    $name = $_POST['your-name'];
    $rating = $_POST['rating'];
    $review = $_POST['your-review'];
    $datetime = date("Y-m-d H:i:s");
    $query = "INSERT INTO reviews (itemID, name, rating, review_text, review_datetime) VALUES ('$itemId', '$name', '$rating', '$review', '$datetime')";
    $result = mysqli_query($db, $query);
    $reviews = returnWholeReviewElement($itemId);
    $cleanReviews =  trim(preg_replace('/\s\s+/', ' ', $reviews));
    $test = htmlspecialchars($cleanReviews, ENT_QUOTES, 'UTF-8');
    echo "<script>
        alert('test');
        let wrapper = document.getElementById('$itemId');
        let child = wrapper.querySelector('.menu-card-reviews');
        child.innerHTML = '$test';
        location.href='/menu.php?item_id=$itemId';
        wrapper.scrollIntoView();
    </script>";
    
}