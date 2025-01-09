// Written by Libert

let userModal = document.querySelector(".user-modal");
let showModalButtons = document.querySelectorAll(".sign-in-link");
let closeModalButtons = document.querySelectorAll(".modal-close");
let submitButtons = document.querySelectorAll(".modal-submit");

let signInForm = document.querySelector(".sign-in.modal-form");
let signUpForm = document.querySelector(".sign-up.modal-form");

let signInLink = document.querySelector(".account-prompt.sign-in");
let signUpLink = document.querySelector(".account-prompt.sign-up");

let mobileButton = document.querySelector(".mobile-burger");
let mobileOptions = document.querySelector(".account-options");


mobileButton.addEventListener("click", (e) => {
    mobileOptions.classList.toggle("visible");
});

// This function is used to get the value of a cookie by name
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}


// This function is used to handle the login process, so that we can convert
// the user's order and cart to their logged in state.
function handleLogin() {
    let hasOrder = getCookie('order');
    fetch('../server/cart.php?saveCart=true')
        .then((response) => response.json())
        .then((resp) => {
            console.log(resp);
        });
    if (hasOrder) {
        fetch(`../server/order.php?saveOrder=${hasOrder}`)
            .then((response) => response.json())
            .then((resp) => {
                console.log(resp);
            });
    }
}


// This shows the modal provided.
function showModal(modal) {
    console.log("modal arriving");
    modal.classList.add("visible");
    document.querySelector("body").classList.add("no-scroll")
};

// This hides any visible modal.
function hideModal() {
    modal = document.querySelector(".modal.visible");
    if (!(modal)) {
        return;
    }
    console.log("modal departing")
    modal.classList.remove("visible");
    document.querySelector("body").classList.remove("no-scroll")
}


// This shows the sign up form and hides the sign in form.
function showSignUpForm() {
    console.log("signInForm departing")
    signInForm.classList.remove("visible");
    console.log("signUpForm arriving")
    signUpForm.classList.add("visible");
}

// This shows the sign in form and hides the sign up form.
function showSignInForm() {
    console.log("signUpForm departing")
    signUpForm.classList.remove("visible");
    console.log("signInForm arriving");
    signInForm.classList.add("visible");
}

// If the user presses the escape key, the modal will close.
window.addEventListener("keydown", (e) => {
    e.key === "Escape" ? hideModal() : null
});

// All buttons that can show the modal get the event listener here.
showModalButtons.forEach(button => button.addEventListener("click", (e) => {
    showSignInForm();
    showModal(userModal)
}));

// Add event listener for mobile buttons too.
let mobileSignUp = document.getElementById("mobile-sign-up");
if (mobileSignUp) {
    mobileSignUp.addEventListener("click", (e) => {
        showSignUpForm();
        showModal(userModal);
    });
}
closeModalButtons.forEach(button => button.addEventListener("click", hideModal));
submitButtons.forEach(button => button.addEventListener("click", hideModal));
signInLink.addEventListener("click", showSignInForm);
signUpLink.addEventListener("click", showSignUpForm);
