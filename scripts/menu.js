let menuItems = document.querySelectorAll(".menu-card-wrapper");
let categoryButtons = document.querySelectorAll(".menu-subheader-option");
let menuSearchTip = document.querySelector(".menu-search-tip");

menuSearchTip.addEventListener("click", (e) => {
    let url = new URL(window.location.href);
    url.searchParams.delete("search");
    if (url.searchParams.has("category") && url.searchParams.get("category") === "all") {
        url.searchParams.delete("category");
    }
    window.history.pushState({}, "", url);
    window.location.reload();
});

menuItems.forEach(item => {
    item.addEventListener("click", (e) => { safeToClick(e.target) ? toggleExpanded(item) : null });
});

function toggleExpanded(item, force = false) {
    let was_expanded = item.classList.contains('expanded');
    menuItems.forEach((menuItem) => {
        menuItem.classList.remove('expanded');
    });
    if (!was_expanded || force) {
        item.classList.add('expanded');
    }

}

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
            window.location.reload();
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

categoryButtons.forEach(button => {
    button.addEventListener("click", (e) => {
        let category = e.target.dataset.category;
        updateCategory(category);
    })
})

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