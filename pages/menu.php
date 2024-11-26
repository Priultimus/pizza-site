<?php require_once('../server/menu_items.php');
$search = null;
$category = 'all';
$title_text = "OUR MENU";
$visible = '';
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (array_key_exists("search", $_GET)) {
        $search = $_GET["search"]; 
        $visible = 'visible';
        $title_text = "Showing results for: $search";
    }

    if (array_key_exists("category", $_GET)) {
        $category = $_GET["category"];
    }

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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (array_key_exists("your-name", $_POST)) {
        $itemId = $_POST['item-id'];
        $name = $_POST['your-name'];
        $rating = $_POST['rating'];
        $review = $_POST['your-review'];
        $datetime = date("Y-m-d H:i:s");
        $stmt = $db->prepare("INSERT INTO reviews (itemID, name, rating, review_text, review_datetime) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiss", $itemId, $name, $rating, $review, $datetime);
        if ($stmt->execute()) {
            $stmt->close();
            $reviews = returnWholeReviewElement($itemId);
            $cleanReviews =  trim(preg_replace('/\s\s+/', ' ', $reviews));
            $test = htmlspecialchars($cleanReviews, ENT_QUOTES, 'UTF-8');
            echo "<script>
              document.addEventListener('DOMContentLoaded', () => {
              let wrapper = document.getElementById('$itemId');
              toggleExpanded(wrapper, force=true);
              });
            </script>";
        } else {
            $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $safeReview = htmlspecialchars($review, ENT_QUOTES, 'UTF-8');
            echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
              errorReview('$itemId', '$safeName', '$safeReview', '$rating');
            });
            </script>";
        }
    }
}

function menuMaker($category) { 
    global $search;
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
                <?php echo getCategories($category) ?>
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

            <user-cart />

        </div>
        
    </main>
    <?php include 'footer.php'; ?>
    <script src='../scripts/menu.js'></script>
</body>

</html>
