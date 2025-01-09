<?php require_once('../server/menu_items.php');
// Wrriten by Libert
// Set the search to null, and the caetgory to all by default.
$search = null;
$category = 'all';
// We will change the title text if the user searches for something.
$title_text = "OUR MENU";
$visible = '';

// We search using url parameters, so we need to check if they exist.
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (array_key_exists("search", $_GET)) {
        $search = $_GET["search"]; 
        $visible = 'visible';
        $title_text = "Showing results for: $search";
    }

    // This allows the user to filter their search by category,
    // by using the already existing category buttons as a filter.
    if (array_key_exists("category", $_GET)) {
        $category = $_GET["category"];
    }

    // This runs if the user was sent here specifically to look at one item in the menu.
    // It expands that menu item's details and scrolls it into view.
    if (array_key_exists("item_id", $_GET) && itemIdExists($_GET["item_id"])) {
        $item_id = $_GET["item_id"];
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                let elem = document.getElementById('$item_id');
                toggleExpanded(elem, force=true);
                elem.scrollIntoView();
            });
            </script>";
    }
}

// This handles a user submitting a review.
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("your-name", $_POST)) {
        // WE grab all the relevavnt information about the review here. 
        $itemId = $_POST['item-id'];
        $name = $_POST['your-name'];
        $rating = $_POST['rating'];
        $review = $_POST['your-review'];
        // We also grab the current date and time.
        $datetime = date("Y-m-d H:i:s");

        $stmt = $db->prepare("INSERT INTO reviews (itemID, name, rating, review_text, review_datetime) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiss", $itemId, $name, $rating, $review, $datetime);

        if ($stmt->execute()) {
            $stmt->close();
            $reviews = returnWholeReviewElement($itemId);
            // This makes sure no XSS is possible, by cleaning up the text in the review.
            $cleanReviews =  trim(preg_replace('/\s\s+/', ' ', $reviews));

            // When we come back to the review page, we want to make sure the item
            // they left a review on is still visible.
            echo "<script>
              document.addEventListener('DOMContentLoaded', () => {
              let wrapper = document.getElementById('$itemId');
              toggleExpanded(wrapper, force=true);
              });
            </script>";

        } else {
            // Also santizing inputs.
            $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $safeReview = htmlspecialchars($review, ENT_QUOTES, 'UTF-8');
    
            // Something went wrong with submitting the review,
            // so we want to show the user an error message.
            echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
              errorReview('$itemId', '$safeName', '$safeReview', '$rating');
            });
            </script>";
        }
    }
}

// This function uses helper functions from menu_Items.php to create the menu items.
function menuMaker($category) { 
    global $search; // We grab the previously set search variable, and use it to see if the user is trying to search something.
    if (isset($search)) {
        $menuItems = fetchMenuItemsBySearch($search, $category);
    } else {
        $menuItems = fetchMenuItemsByCategory($category);
    }
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
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
    <link rel="stylesheet" type="text/css" href="../css/reviews.css">
    <link rel="stylesheet" type="text/css" href="../css/cart.css">
    <script src="../scripts/cart.js" defer></script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main>

        <?php include 'orderModal.php'; ?>
        <header class="menu-subheader">
            <button class="mobile menu-popout">
                <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="1" y="3" width="30" height="3" fill="currentColor" />
                    <rect x="1" y="23" width="30" height="3" fill="currentColor" />
                    <rect x="1" y="13" width="30" height="3" fill="currentColor" />
                </svg>
            </button>
            <div class="menu-options">
                <button data-category="all" class="menu-subheader-option <?php if ($category == 'all') {echo 'selected';} ?>">ALL</button>
                <?php // Categories are automatically found and generated here
                echo getCategories($category) ?>
            </div>
            <form class="menu-search-wrapper" method="GET">
                <input class="menu-search" type="text" id="search" name="search"
                    placeholder="FIND YOUR PERFECT MEAL..." <?php if (isset($search)) {echo "value='$search'";} ?>>
                <input type="hidden" name="category" id="category" value="<?php echo $category; ?>">
                <button type="submit" class="menu-search-button">
                    <img src="../images/search.svg" alt="Search Icon" />
                </button>
            </form>
        </header>
        <div class="mobile menu-subheader">
            <?php echo getCategories($category) ?>
        </div>
        <div class='menu-title'>
          <h1><?php echo $title_text ?></h1>
          <a class="menu-search-tip <?php echo $visible ?>">Clear your search</a>
        </div>
        <div class="menu">
            <div class="menu-cards">
                <?php echo menuMaker($category); ?>
            </div>
            <!-- Use custom JS WebComponent to handle cart. Implementation is in scripts/cart.js -->
            <user-cart />

        </div>
        
    </main>
    <?php include 'footer.php'; ?>
    <script src='../scripts/menu.js'></script>
</body>

</html>
