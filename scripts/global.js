let userModal = document.querySelector(".user-modal");
let showModalButtons = document.querySelectorAll(".sign-in-link");
let closeModalButtons = document.querySelectorAll(".modal-close");
let submitButtons = document.querySelectorAll(".modal-submit");

let signInForm = document.querySelector(".sign-in.modal-form");
let signUpForm = document.querySelector(".sign-up.modal-form");

let signInLink = document.querySelector(".account-prompt.sign-in");
let signUpLink = document.querySelector(".account-prompt.sign-up");

function showModal(modal) {
    console.log("modal arriving");
    modal.classList.add("visible");
    document.querySelector("body").classList.add("no-scroll")
};

function hideModal() {
    modal = document.querySelector(".modal.visible");
    if (!(modal)) {
        return;
    }
    console.log("modal departing")
    modal.classList.remove("visible");
    document.querySelector("body").classList.remove("no-scroll")
}

function showSignUpForm() {
    console.log("signInForm departing")
    signInForm.classList.remove("visible");
    console.log("signUpForm arriving")
    signUpForm.classList.add("visible");
}

function showSignInForm() {
    console.log("signUpForm departing")
    signUpForm.classList.remove("visible");
    console.log("signInForm arriving");
    signInForm.classList.add("visible");
}

window.addEventListener("keydown", (e) => {
    e.key === "Escape" ? hideModal() : null
});

showModalButtons.forEach(button => button.addEventListener("click", (e) => {
    showModal(userModal)
}));
closeModalButtons.forEach(button => button.addEventListener("click", hideModal));
submitButtons.forEach(button => button.addEventListener("click", hideModal));
signInLink.addEventListener("click", showSignInForm);
signUpLink.addEventListener("click", showSignUpForm);
