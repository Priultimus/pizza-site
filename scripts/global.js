let modal = document.querySelector(".modal");
let signInButton = document.querySelector(".sign-in");
let submitButton = document.querySelector(".submit");

signInButton.onclick = function () {
    modal.style.display = "block";
};

submitButton.onclick = function () {
    modal.style.display = "none";
};

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}