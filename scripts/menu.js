// Written by Libert

let menuItems = document.querySelectorAll(".menu-card-wrapper");
let categoryButtons = document.querySelectorAll(".menu-subheader-option");
let menuSearchTip = document.querySelector(".menu-search-tip");
let menuBurgerButton = document.querySelector(".mobile.menu-popout");

menuBurgerButton.addEventListener("click", (e) => {
    let menu = document.querySelector(".mobile.menu-subheader");
    menu.classList.toggle("visible");
});

// Add error message to review form
function errorReview(itemId, name, review, rating, message = 'Something went wrong while submitting your review. Please try again') {
    let wrapper = document.getElementById(itemId);
    let nameInput = document.getElementById(`your-name-${itemId}`);
    let starzero = document.getElementById(`star0-${itemId}`);
    let stars = document.getElementById(`star${rating}-${itemId}`);
    let reviewInput = document.getElementById(`your-review-${itemId}`);
    let err = document.createElement('p');
    let reviewCard = reviewInput.parentElement;

    toggleExpanded(wrapper, force = true);
    nameInput.value = name;
    reviewInput.value = review;
    stars.checked = true;
    starzero.checked = false;
    err.id = reviewInput.id + '-error';
    err.className = 'error-message';
    err.style.color = 'red';
    err.textContent = message;
    reviewCard.appendChild(err);
    nameInput.style.border = '2px solid red';
    reviewInput.style.border = '2px solid red';
}

menuItems.forEach(item => {
    item.addEventListener("click", (e) => { safeToClick(e.target) ? toggleExpanded(item) : null });
});

// Expand the menu item when the user clicks on the review button.
function toggleExpanded(item, force = false) {
    let was_expanded = item.classList.contains('expanded');
    menuItems.forEach((menuItem) => {
        menuItem.classList.remove('expanded');
    });
    if (!was_expanded || force) {
        item.classList.add('expanded');
    }

}

// This function just checks to make sure the user was ACTUALLY clicking on the menu item, and not like, the add to cart button.
function safeToClick(target) {
    let shouldApprove = true;
    if (!(target.classList.length === 0)) {
        target.classList.forEach((c) => {
            if (c.includes('review') || c.includes('star') || c.includes('button')) {
                shouldApprove = false;
            }
        });
    }
    return shouldApprove;
}


// This function updates the category on the website and the URL.
function updateCategory(category) {
    category = category.toLowerCase();
    console.log(`Update category called: ${category}`)
    categoryButtons.forEach(button => {
        if (button.dataset.category === category) {
            button.classList.add("selected");
            let url = new URL(window.location.href);
            if (!(category === "all")) {
                url.searchParams.set("category", category);
            } else {
                url.searchParams.delete("category");
            }
            window.history.pushState({}, "", url);

            window.location.reload(); // I don't liek doing this but I can't find any other way to ensure the categories stay consistent.

        } else {
            button.classList.remove("selected");
        }
    });
    menuItems.forEach(item => {
        let childItem = item.querySelector(".menu-card");
        if (category === "all") {
            console.log("all");
            item.classList.remove("hidden");
        } else if (childItem.dataset.category === category) {
            item.classList.remove("hidden");
        } else {
            item.classList.add("hidden");
        }
    });
}

// This function updates the search on the website and the URL.
categoryButtons.forEach(button => {
    button.addEventListener("click", (e) => {
        let category = e.target.dataset.category;
        updateCategory(category);
    })
})

// When the user wants to clear their search, this button can do that.
menuSearchTip.addEventListener("click", (e) => {
    let url = new URL(window.location.href);
    url.searchParams.delete("search");
    if (url.searchParams.has("category") && url.searchParams.get("category") === "all") {
        url.searchParams.delete("category");
    }
    window.history.pushState({}, "", url);
    window.location.reload();
});

// Also leave search if the user presses escape AND there's no modal open AND there's a search query.
window.addEventListener("keydown", (e) => {
    let url = new URL(window.location.href);
    if (e.key === "Escape" && !(document.querySelector(".modal.visible")) && url.searchParams.has("search")) {
        url.searchParams.delete("search");
        if (url.searchParams.has("category") && url.searchParams.get("category") === "all") {
            url.searchParams.delete("category");
        }
        window.history.pushState({}, "", url);
        window.location.reload();
    }
});
