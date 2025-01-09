<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pizza Shop</title>
    <link rel="stylesheet" type="text/css" href="../css/index.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <?php include 'orderModal.php'; ?>
        <div class="upper">
            <h1>SATISIFY YOUR CRAVINGS</h1>
            <p class="tag-line">We make the best pizza. We love pizza. Stuff like that.</p>
            <button onclick="startOrder();"class="cta-large">ORDER NOW</button>
        </div>
        <hr>
        <div class="lower">
            <h2 class="lower-title">Here's a taste of what's in store...</h2>
            <div class="menu-highlight-cards">
                <?php require_once('../server/menu_items.php'); 

                // This calls fetchHighlights, which generates the menu items for the landing page.
                foreach(fetchHighlights() as $itemId => $item) { echo $item; }; ?>
            </div>

            <div class="go-to-menu">
                <h2>Still not satisfied?</h2>
                <button class="cta-long" onClick="location.href='../pages/menu.php'">Click here to go to our menu</button>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>

</html>