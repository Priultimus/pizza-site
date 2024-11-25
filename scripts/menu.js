let menuItems = document.querySelectorAll(".menu-card-wrapper");
menuItems.forEach(item => {
    item.addEventListener("click", (e) => {
        if (e.target.classList.contains(".menu-card-wrapper")) {
            item.classList.remove("expanded")
        }
        item.classList.add("expanded")
    })
})