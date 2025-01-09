// Written by Libert

let orderModal = document.querySelector(".order-modal");
let orderTypeButton = document.querySelector(".order-type-button");
let orderTypeOptions = document.querySelector(".order-type-options");

// This allows us to autofill the user's address.
mapboxsearch.config.accessToken = mapboxsearch.config.accessToken = 'pk.eyJ1IjoibGliZXJ0YiIsImEiOiJjbTN3M2E3eTExMTdpMm5wd2c3bXdtZDB0In0.ZMRFNzmM7NlWkYVetakZIQ'
const autofill = mapboxsearch.autofill({
    options: {
        country: 'ca',
        proximity: 'ip'
    }
});

// Define the address object.
let address = {
    street: null,
    province: null,
    city: null,
    postal_code: null
};

// When the autofill element retrieves information, we want to make sure we store it temporarily in the address object.
autofill.addEventListener('retrieve', (event) => {
    const info = event.detail.features[0].properties;
    address.street = info.address_line1;
    address.province = info.address_level1;
    address.city = info.address_level2;
    address.postal_code = info.postcode;
    let hasError = document.querySelector(".error-message.order-modal");
    if (hasError) {
        hasError.classList.add("hidden");
        let addr = document.querySelector(".address-autocomplete");
        addr.classList.remove("has-error");
    }
    console.log(info) // for debugging purposes
})


// This function is called when the user submits the order form. It informs the server of the user's order.
function pushOrder(orderData) {
    let order = { order: orderData }
    fetch("../server/order.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(order)
    })
        .then((response) => { response.json() })
        .then((json) => console.log(json))
}

// This just makes it so that when they pick which order type the button that pretends to be a dropdown behaves like one.
// I use a fake drop down instead of a real one because the real ones don't let me style them nearly as much unfortunately :(
function handleOption(opt) {
    orderTypeButton.innerHTML = opt;
    orderType = opt.toLowerCase();
    orderTypeOptions.classList.remove("visible");
    let submit = document.querySelector(".order-modal.modal-submit")
    submit.classList.remove("disabled");
    submit.type = "submit";
    if (opt === "Delivery") {
        document.querySelector(".address-autocomplete").classList.add("visible");
    } else {
        document.querySelector(".address-autocomplete").classList.remove("visible");
    }
}

// This is called when the user submits the form, and does the due dillgence of validating their information.
// We use hidden values to send information with the form rather than actually taking what the user inputted, since we have 
// more than just the address line 1, for example. We have the city, province, etc.
function handleSubmit(form) {
    const addressField = document.querySelector("#address-search");
    if (addressField) {
        addressField.remove();
    }
    const orderTypeElem = document.createElement("input");
    orderTypeElem.type = "hidden";
    orderTypeElem.value = orderType;
    orderTypeElem.id = "order-type"
    orderTypeElem.name = "order-type"
    form.appendChild(orderTypeElem);
    console.log(orderType);
    if (orderType === "delivery") {
        if (!address.street) {
            const addr = document.querySelector(".address-autocomplete");
            errorMessage = document.createElement('p');
            errorMessage.id = addr.id + "-error";
            errorMessage.classList.add('error-message');
            errorMessage.classList.add('order-modal');
            errorMessage.style.color = 'red';
            errorMessage.textContent = "Please choose an address from the dropdown.";
            // Append the error message after the input field
            addr.classList.add("has-error");
            form.appendChild(errorMessage);
            return false;
        }
        const addressElem = document.createElement("input");
        addressElem.type = "hidden";
        addressElem.value = JSON.stringify(address);
        addressElem.id = "address-data"
        addressElem.name = "address-data"
        form.appendChild(addressElem);
    }
    return true;
}

// This grabs the cookie for the user.
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// This function is called when the user clicks the BIG order button. It checks if they have an order already, and if they do, it sends them to the menu page.
function startOrder() {
    let hasOrder = getCookie('order');
    if (!hasOrder) {
        showModal(orderModal);
    } else {
        window.location.href = "../pages/menu.php";
    }
}

orderTypeButton.addEventListener("click", () => {
    orderTypeOptions.classList.toggle("visible")
});
