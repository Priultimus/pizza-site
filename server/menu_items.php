<?php 

require_once('../database/database.php');
$db = db_connect();

function getCategories() {
    $query = 'SELECT DISTINCT category FROM menu_items';
    global $db;
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $category = $row['category'];
        $categoryText = strtoupper($category);
        echo "<button data-category='$category' class='menu-subheader-option'>{$categoryText}</button>";
    }
}

function calcRating($rating) {
  if (empty($rating) || $rating >= 5) {
            $rating = 5; // :)
        } elseif ($rating >= 4.5 ) {
            $rating = 4.5;
        } elseif ($rating >= 4 ) {
            $rating = 4;
        } elseif ($rating >= 3.5) {
            $rating = 3.5;
        } elseif ($rating >= 3 ) {
            $rating = 3;
        } elseif ($rating >= 2.5 ) {
            $rating = 2.5;
        } elseif ($rating >= 2 ) {
            $rating = 2;
        } elseif ($rating >= 1.5 ) {
            $rating = 1.5;
        } elseif ($rating >= 1 ) {
            $rating = 1;
        } elseif ($rating >= 0.5 ) {
            $rating = 0.5;
        } else {
            $rating = 0;
        }
      return $rating;
}

function itemIdExists($itemId) {
    $query = "SELECT * FROM menu_items WHERE itemID = $itemId";
    global $db;
    $result = mysqli_query($db, $query);
    return mysqli_num_rows($result) > 0;
}

function fetchMenuItemReviews($menuItemID) {
    $query = "SELECT * FROM reviews WHERE itemID = $menuItemID ORDER BY review_datetime DESC";
    global $db;
    $result = mysqli_query($db, $query);
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $rating = $row['rating'];
        $review = $row['review_text'];
        $datetime = $row['review_datetime'];
        $date = substr($datetime, 0, -8);

        $reviews[] = "
            <div class='review'>
              <div class='review-details'>
                <h2>$name</h2>
                <img class='menu-card-stars' src='../images/$rating-stars.svg' alt='Rating: $rating' />
              </div>
              <p>$review</p>
              <p class='review-date'>$date</p>
            </div>";
    }
    return $reviews;
}

function returnWholeReviewElement($itemId) {
    $res = "<div class='menu-card-reviews'>
      <h1 class='reviews-title'>ITEM REVIEWS</h1>
      <div class='reviews'>
        <form class='review create-review-card' method='POST'>
          <div class='create-review review-details'>
            <input class='create-review-name' type='text' id='your-name-$itemId' name='your-name' placeholder='Enter your name' />
            <div class='create-review-stars'>
              <input class='radio-star' disabled hidden checked type='radio' id='star0-$itemId' name='rating' value='0' autocomplete='off' />
              <label class='star-label hidden'></label>
              <input class='radio-star' type='radio' id='star1-$itemId' name='rating' value='1' />
              <label class='star-label' for='star1-$itemId' title='1 star'>
                  <svg class='star-svg' width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M7.67591 0.420309L9.25493 3.61983C9.36474 3.84235 9.57705 3.99655 9.82265 4.03218L13.3536 4.54528C13.9721 4.63522 14.2189 5.3951 13.7715 5.8311L11.2165 8.32156C11.039 8.49474 10.9578 8.74442 10.9998 8.98886L11.6029 12.5055C11.7086 13.1214 11.062 13.591 10.5089 13.3004L7.35088 11.6402C7.13127 11.5249 6.86873 11.5249 6.64912 11.6402L3.49108 13.3004C2.93797 13.5913 2.2914 13.1214 2.39712 12.5055L3.00017 8.98886C3.04222 8.74442 2.96104 8.49474 2.78348 8.32156L0.228484 5.8311C-0.218911 5.3948 0.0278572 4.63493 0.646383 4.54528L4.17735 4.03218C4.42295 3.99655 4.63526 3.84235 4.74507 3.61983L6.32409 0.420309C6.60035 -0.140103 7.39936 -0.140103 7.67591 0.420309Z' fill='currentColor' />
                </svg>
              </label>
              <input class='radio-star' type='radio' id='star2-$itemId' name='rating' value='2' />
              <label class='star-label' for='star2-$itemId' title='2 stars'>
                <svg class='star-svg' width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M7.67591 0.420309L9.25493 3.61983C9.36474 3.84235 9.57705 3.99655 9.82265 4.03218L13.3536 4.54528C13.9721 4.63522 14.2189 5.3951 13.7715 5.8311L11.2165 8.32156C11.039 8.49474 10.9578 8.74442 10.9998 8.98886L11.6029 12.5055C11.7086 13.1214 11.062 13.591 10.5089 13.3004L7.35088 11.6402C7.13127 11.5249 6.86873 11.5249 6.64912 11.6402L3.49108 13.3004C2.93797 13.5913 2.2914 13.1214 2.39712 12.5055L3.00017 8.98886C3.04222 8.74442 2.96104 8.49474 2.78348 8.32156L0.228484 5.8311C-0.218911 5.3948 0.0278572 4.63493 0.646383 4.54528L4.17735 4.03218C4.42295 3.99655 4.63526 3.84235 4.74507 3.61983L6.32409 0.420309C6.60035 -0.140103 7.39936 -0.140103 7.67591 0.420309Z' fill='currentColor' />
                </svg>
              </label>
              <input class='radio-star' type='radio' id='star3-$itemId' name='rating' value='3' />
              <label class='star-label' for='star3-$itemId' title='3 stars'>
                <svg class='star-svg' width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M7.67591 0.420309L9.25493 3.61983C9.36474 3.84235 9.57705 3.99655 9.82265 4.03218L13.3536 4.54528C13.9721 4.63522 14.2189 5.3951 13.7715 5.8311L11.2165 8.32156C11.039 8.49474 10.9578 8.74442 10.9998 8.98886L11.6029 12.5055C11.7086 13.1214 11.062 13.591 10.5089 13.3004L7.35088 11.6402C7.13127 11.5249 6.86873 11.5249 6.64912 11.6402L3.49108 13.3004C2.93797 13.5913 2.2914 13.1214 2.39712 12.5055L3.00017 8.98886C3.04222 8.74442 2.96104 8.49474 2.78348 8.32156L0.228484 5.8311C-0.218911 5.3948 0.0278572 4.63493 0.646383 4.54528L4.17735 4.03218C4.42295 3.99655 4.63526 3.84235 4.74507 3.61983L6.32409 0.420309C6.60035 -0.140103 7.39936 -0.140103 7.67591 0.420309Z' fill='currentColor' />
                </svg>
              </label>
              <input class='radio-star' type='radio' id='star4-$itemId' name='rating' value='4' />
              <label class='star-label' for='star4-$itemId' title='4 stars'>
                <svg class='star-svg' width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M7.67591 0.420309L9.25493 3.61983C9.36474 3.84235 9.57705 3.99655 9.82265 4.03218L13.3536 4.54528C13.9721 4.63522 14.2189 5.3951 13.7715 5.8311L11.2165 8.32156C11.039 8.49474 10.9578 8.74442 10.9998 8.98886L11.6029 12.5055C11.7086 13.1214 11.062 13.591 10.5089 13.3004L7.35088 11.6402C7.13127 11.5249 6.86873 11.5249 6.64912 11.6402L3.49108 13.3004C2.93797 13.5913 2.2914 13.1214 2.39712 12.5055L3.00017 8.98886C3.04222 8.74442 2.96104 8.49474 2.78348 8.32156L0.228484 5.8311C-0.218911 5.3948 0.0278572 4.63493 0.646383 4.54528L4.17735 4.03218C4.42295 3.99655 4.63526 3.84235 4.74507 3.61983L6.32409 0.420309C6.60035 -0.140103 7.39936 -0.140103 7.67591 0.420309Z' fill='currentColor' />
                </svg>
              </label>
              <input class='radio-star' type='radio' id='star5-$itemId' name='rating' value='5' />
              <label class='star-label' for='star5-$itemId' title='5 stars'>
                <svg class='star-svg' width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'> 
                  <path d='M7.67591 0.420309L9.25493 3.61983C9.36474 3.84235 9.57705 3.99655 9.82265 4.03218L13.3536 4.54528C13.9721 4.63522 14.2189 5.3951 13.7715 5.8311L11.2165 8.32156C11.039 8.49474 10.9578 8.74442 10.9998 8.98886L11.6029 12.5055C11.7086 13.1214 11.062 13.591 10.5089 13.3004L7.35088 11.6402C7.13127 11.5249 6.86873 11.5249 6.64912 11.6402L3.49108 13.3004C2.93797 13.5913 2.2914 13.1214 2.39712 12.5055L3.00017 8.98886C3.04222 8.74442 2.96104 8.49474 2.78348 8.32156L0.228484 5.8311C-0.218911 5.3948 0.0278572 4.63493 0.646383 4.54528L4.17735 4.03218C4.42295 3.99655 4.63526 3.84235 4.74507 3.61983L6.32409 0.420309C6.60035 -0.140103 7.39936 -0.140103 7.67591 0.420309Z' fill='currentColor' />
                </svg>
              </label>
            </div>
          </div>
          <textarea class='create-review-text' id='your-review-$itemId' name='your-review' placeholder='Tell us what you think here!'></textarea>
          <input type='hidden' name='item-id' value='$itemId' />
          <button type='submit' class='create-review-submit'>SUBMIT</button>
        </form>";
    foreach (fetchMenuItemReviews($itemId) as $review) { $res .= $review; };
    $res .= "</div></div>";
    return $res;
        
}

function fetchMenuItemById($itemId) {
    $query = "SELECT * FROM menu_items WHERE itemID = $itemId";
    global $db;
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $desc = $row['description'];
    $ratingQuery = "SELECT ROUND(AVG(rating), 1) AS rating FROM reviews WHERE itemID = $itemId";
    $ratingResult = mysqli_query($db, $ratingQuery);
    $ratingRow = mysqli_fetch_assoc($ratingResult);
    $rating = calcRating($ratingRow['rating']);
    $image = $row['image'];
    return "<div class='menu-card'>
              <div class='menu-card-panel'>
                <img class='menu-card-image' src='../$image' alt='$desc' />
                <div class='menu-card-details'>
                  <h3>$name</h3>
                  <p>$desc</p>
                </div>
                <img class='menu-card-stars' src='../images/$rating-stars.svg' alt='Rating: $rating' />
              </div>
              <button class='menu-card-button'>
                <svg width='25' height='23' viewBox='0 0 25 23' fill='none' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M10.1677 16.605C8.63475 16.605 7.3877 17.852 7.3877 19.3849C7.3877 20.9182 8.63475 22.1656 10.1677 22.1656C11.7005 22.1656 12.9477 20.9182 12.9477 19.3849C12.9477 17.852 11.7005 16.605 10.1677 16.605ZM10.1677 20.3693C9.62519 20.3693 9.18392 19.9276 9.18392 19.3848C9.18392 18.8424 9.62519 18.4011 10.1677 18.4011C10.7101 18.4011 11.1515 18.8424 11.1515 19.3848C11.1515 19.9276 10.7101 20.3693 10.1677 20.3693Z' fill='currentColor' />
                  <path d='M19.44 16.605C17.9071 16.605 16.6599 17.852 16.6599 19.3849C16.6599 20.9182 17.907 22.1656 19.44 22.1656C20.973 22.1656 22.2203 20.9182 22.2203 19.3849C22.2203 17.852 20.973 16.605 19.44 16.605ZM19.44 20.3693C18.8975 20.3693 18.4561 19.9276 18.4561 19.3848C18.4561 18.8424 18.8975 18.4011 19.44 18.4011C19.9826 18.4011 20.4241 18.8424 20.4241 19.3848C20.4241 19.9276 19.9827 20.3693 19.44 20.3693Z' fill='currentColor' />
                  <path d='M24.8123 3.9117C24.6423 3.69172 24.3799 3.56287 24.1019 3.56287H6.20009L5.44831 0.672028C5.34533 0.276259 4.988 0 4.57906 0H0.898112C0.402115 0 0 0.402115 0 0.898113C0 1.39411 0.402115 1.79623 0.898112 1.79623H3.88464L4.63031 4.66372C4.63402 4.67977 4.63821 4.69569 4.64276 4.71138L7.41578 15.3745C7.51876 15.7703 7.87609 16.0465 8.28503 16.0465H21.3225C21.7314 16.0465 22.0888 15.7703 22.1918 15.3745L24.9711 4.68695C25.0411 4.41812 24.9824 4.1318 24.8123 3.9117ZM20.6281 14.2505H8.97945L6.66723 5.35922H22.9404L20.6281 14.2505Z' fill='currentColor' />
                </svg>
                <span>Add to Cart</span>
              </button>
            </div>";
}

function fetchMenuItemsByCategory($category) {
    if ($category == "all") {
        $query = "SELECT * FROM menu_items";
    } else {
        $query = "SELECT * FROM menu_items WHERE category = $category";
    }
    global $db;
    $result = mysqli_query($db, $query);
    $menuItems = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['itemID'];
        $name = $row['name'];
        $desc = $row['description'];
        $itemCategory = $row['category'];
        $ratingQuery = "SELECT ROUND(AVG(rating), 1) AS rating FROM reviews WHERE itemID = $id";
        $ratingResult = mysqli_query($db, $ratingQuery);
        $ratingRow = mysqli_fetch_assoc($ratingResult);
        $rating = calcRating($ratingRow['rating']);
        $image = $row['image'];

        $menuItems[$id] = "
            <div data-category='$itemCategory' class='menu-card'>
              <div class='menu-card-panel'>
                <img class='menu-card-image' src='../$image' alt='$desc' />
                <div class='menu-card-details'>
                  <h3>$name</h3>
                  <p>$desc</p>
                </div>
                <img class='menu-card-stars' src='../images/$rating-stars.svg' alt='Rating: $rating' />
              </div>
              <button class='menu-card-button'>
                <svg width='25' height='23' viewBox='0 0 25 23' fill='none' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M10.1677 16.605C8.63475 16.605 7.3877 17.852 7.3877 19.3849C7.3877 20.9182 8.63475 22.1656 10.1677 22.1656C11.7005 22.1656 12.9477 20.9182 12.9477 19.3849C12.9477 17.852 11.7005 16.605 10.1677 16.605ZM10.1677 20.3693C9.62519 20.3693 9.18392 19.9276 9.18392 19.3848C9.18392 18.8424 9.62519 18.4011 10.1677 18.4011C10.7101 18.4011 11.1515 18.8424 11.1515 19.3848C11.1515 19.9276 10.7101 20.3693 10.1677 20.3693Z' fill='currentColor' />
                  <path d='M19.44 16.605C17.9071 16.605 16.6599 17.852 16.6599 19.3849C16.6599 20.9182 17.907 22.1656 19.44 22.1656C20.973 22.1656 22.2203 20.9182 22.2203 19.3849C22.2203 17.852 20.973 16.605 19.44 16.605ZM19.44 20.3693C18.8975 20.3693 18.4561 19.9276 18.4561 19.3848C18.4561 18.8424 18.8975 18.4011 19.44 18.4011C19.9826 18.4011 20.4241 18.8424 20.4241 19.3848C20.4241 19.9276 19.9827 20.3693 19.44 20.3693Z' fill='currentColor' />
                  <path d='M24.8123 3.9117C24.6423 3.69172 24.3799 3.56287 24.1019 3.56287H6.20009L5.44831 0.672028C5.34533 0.276259 4.988 0 4.57906 0H0.898112C0.402115 0 0 0.402115 0 0.898113C0 1.39411 0.402115 1.79623 0.898112 1.79623H3.88464L4.63031 4.66372C4.63402 4.67977 4.63821 4.69569 4.64276 4.71138L7.41578 15.3745C7.51876 15.7703 7.87609 16.0465 8.28503 16.0465H21.3225C21.7314 16.0465 22.0888 15.7703 22.1918 15.3745L24.9711 4.68695C25.0411 4.41812 24.9824 4.1318 24.8123 3.9117ZM20.6281 14.2505H8.97945L6.66723 5.35922H22.9404L20.6281 14.2505Z' fill='currentColor' />
                </svg>
                <span>Add to Cart</span>
              </button>
            </div>";
    }
    return $menuItems;
}
