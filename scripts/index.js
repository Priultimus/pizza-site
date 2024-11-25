let orderModal = document.querySelector(".order-modal");
let startOrder = document.querySelector(".cta-large");
let orderTypeButton = document.querySelector(".order-type-button");
let orderTypeOptions = document.querySelector(".order-type-options");

mapboxsearch.config.accessToken = 'pk.token'
const autofill = mapboxsearch.autofill({
    options: {
        country: 'ca',
        proximity: 'ip'
    }
});

let address = {
    street: null,
    province: null,
    city: null,
    postal_code: null
};

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
    console.log(info)
})

let orderType = null;

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


startOrder.addEventListener("click", () => { showModal(orderModal) });
orderTypeButton.addEventListener("click", () => {
    orderTypeOptions.classList.toggle("visible")
});
