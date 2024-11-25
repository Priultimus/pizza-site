<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pizza Shop</title>
    <link rel="stylesheet" type="text/css" href="css/globals.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0-beta.24/web.js"></script>
</head>

<body>
    <?php include 'pages/header.php'; ?>
    <main>
        <div class="modal order-modal">
            <form method='get' onsubmit="handleSubmit(this);" class="modal-content start-order">
                <button class="modal-close">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.00012207" y="17.6777" width="25" height="2.5" rx="1"
                            transform="rotate(-45 0.00012207 17.6777)" fill="currentColor" />
                        <rect x="1.73669" width="25" height="2.5" rx="1" transform="rotate(44 1.73669 0)"
                            fill="currentColor" />
                    </svg>
                </button>
                <h1>Select an order type:</h1>
                <button type="button" class="order-type-button">
                    <p>Choose one</p>
                    <svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.415638 2.2881C-0.126682 1.72872 -0.126682 0.932209 0.347847 0.423795C0.890177 -0.135483 1.73757 -0.135483 2.22906 0.3899L8.99127 7.55888L15.7534 0.3899C16.2619 -0.135483 17.0923 -0.118536 17.6516 0.423795C18.1431 0.915282 18.1092 1.72872 17.5837 2.2881L10.7369 9.55868C9.6352 10.7281 8.36415 10.7281 7.26262 9.55868L0.415638 2.2881Z"
                            fill="currentColor" />
                    </svg>
                </button>
                <ul class="order-type-options">
                    <li class="order-type-option" onclick="handleOption('Delivery')">Delivery</li>
                    <li class="order-type-option" onclick="handleOption('Take-out')">Take-out</li>
                </ul>
                <input class="address-autocomplete" type="text" name="address-search" id="address-search"
                    autocomplete="address-line1" placeholder="Start typing your address..." />
                <button type="button" class="modal-submit order-modal disabled">Submit</button>
            </form>
        </div>
        <div class="upper">
            <h1>SATISIFY YOUR CRAVINGS</h1>
            <p class="tag-line">We make the best pizza. We love pizza. Stuff like that.</p>
            <button class="cta-large">ORDER NOW</button>
        </div>
        <hr>
        <div class="lower">
            <h2 class="lower-title">Here's a taste of what's in store...</h2>
            <div class="menu-highlight-cards">
                <!-- Menu Card 1 -->
                <div class="menu-card">
                    <div class="menu-card-panel">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="menu-card-details">
                            <h3>ITEM NAME</h3>
                            <p>ITEM DESCRIPTION</p>
                            <p>ITEM RATING AS STARS</p>
                        </div>
                    </div>
                    <button class="menu-highlight-card-button">Find Out More</button>
                </div>
                <!-- Menu Card 2 -->
                <div class="menu-card">
                    <div class="menu-card-panel">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="menu-card-details">
                            <h3>ITEM NAME</h3>
                            <p>ITEM DESCRIPTION</p>
                            <p>ITEM RATING AS STARS</p>
                        </div>
                    </div>
                    <button class="menu-highlight-card-button">Find Out More</button>
                </div>
                <!-- Menu Card 3 -->
                <div class="menu-card">
                    <div class="menu-card-panel">
                        <img src="images/placeholder_image.png" alt="ITEM ALT" />
                        <div class="menu-card-details">
                            <h3>ITEM NAME</h3>
                            <p>ITEM DESCRIPTION</p>
                            <p>ITEM RATING AS STARS</p>
                        </div>
                    </div>
                    <button class="menu-highlight-card-button">Find Out More</button>
                </div>
            </div>

            <div class="go-to-menu">
                <h2>Still not satisfied?</h2>
                <button class="cta-long" onClick="location.href='/menu.html'">Click here to go to our menu</button>
            </div>
        </div>
    </main>

    <?php include 'pages/footer.php'; ?>

    <script src="scripts/global.js" defer></script>
    <script src="scripts/index.js" defer></script>
</body>

</html>