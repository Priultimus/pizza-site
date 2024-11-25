let menuItems = document.querySelectorAll(".menu-card-wrapper");
let categoryButtons = document.querySelectorAll(".menu-subheader-option");

menuItems.forEach(item => {
    item.addEventListener("click", (e) => {
        console.log(e.target);
        if (e.target.classList.contains("menu-card-wrapper")) {
            console.log("hit");
            e.target.classList.remove("expanded");
            console.log(e.target.classList);
        }
        item.classList.add("expanded");
    })
})

function updateCategory(category) {
    category = category.toLowerCase();
    console.log(`Update category called: ${category}`)
    categoryButtons.forEach(button => {
        if (button.dataset.category === category) {
            button.classList.add("selected");
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
            console.log(`show ${item.id}`);
            item.classList.remove("hidden");
        } else {
            console.log(`hid ${item.id} category: ${childItem.dataset.category} statement: ${childItem.dataset.category === category}`);
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