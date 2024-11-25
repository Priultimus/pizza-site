let menuItems = document.querySelectorAll(".menu-card-wrapper");
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