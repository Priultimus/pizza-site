let userModal = document.querySelector(".user-modal");
let showModalButton = document.querySelector(".sign-in-link");
let closeModalButton = document.querySelector(".modal-close");
let submitButton = document.querySelector(".modal-submit");

let signInForm = document.querySelector(".sign-in.modal-form");
let signUpForm = document.querySelector(".sign-up.modal-form");

let signInLink = document.querySelector(".account-prompt.sign-in");
let signUpLink = document.querySelector(".account-prompt.sign-up");

function showModal(modal) {
    console.log("modal arriving");
    modal.classList.add("visible");
};

function hideModal() {
    modal = document.querySelector(".modal.visible");
    console.log("modal departing")
    modal.classList.remove("visible");
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

showModalButton.addEventListener("click", () => showModal(userModal));
closeModalButton.addEventListener("click", hideModal);
submitButton.addEventListener("click", hideModal);
signInLink.addEventListener("click", showSignInForm);
signUpLink.addEventListener("click", showSignUpForm);
