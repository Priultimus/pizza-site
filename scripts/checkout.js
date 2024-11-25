//Initializing constants
const checkbox = document.getElementById('billing-checkbox');
const billingForm = document.querySelector(".billing.form");

const cc = document.getElementById('cc');
const exp = document.getElementById('exp');
const cvv = document.getElementById('cvv');

const nameField = document.getElementById('name');
const address = document.getElementById('address');
const city = document.getElementById('city');
const province = document.getElementById('province');
const postal = document.getElementById('postal');

// Function responsible for preventing billing address input if it is the same as delivery address
// If it is checked, you will need to input a billing address
// If it is not checked, it will use your delivery address as your billing address 
function whenClicked() {
    if (checkbox.checked) {
        billingForm.classList.remove('disabled')
    } else {
        billingForm.classList.add('disabled');
        clearError(nameField);
        clearError(address);
        clearError(city);
        clearError(province);
        clearError(postal);
    }
}

//Function responsible for displaying error messages and highlighting fields
function showError(input, message) {
    let errorMessage = document.getElementById(input.id + "-error");
    if (errorMessage){
        errorMessage.textContent = message;
    } else {
        // Create an error message element
        errorMessage = document.createElement('p');
        errorMessage.id = input.id + "-error";
        errorMessage.className = 'error-message';
        errorMessage.style.color = 'red';
        errorMessage.textContent = message;

        // Append the error message after the input field
        input.style.border = '2px solid red';
        input.parentNode.appendChild(errorMessage);
    }
}

function clearError(field){
    let errorMessage = document.getElementById(field.id + "-error");
    if (errorMessage){
        field.parentNode.removeChild(errorMessage);//Remove the error message
        field.style.border = ''; // Reset border style
    }
}

function onFocused(field){
    clearError(field)
}

//Credit Card Verification
function validateCreditCardField(field){
    const ccRegex = /^[0-9]{16}$/;
    if (!ccRegex.test(field.value.trim())){
        showError(field, 'Please enter a valid 16-digit credit card number.');
        return false;
    } 
    return true;
}
//Expiration Date Verification
function validateExpirationDateField(field){
    const expRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (!expRegex.test(field.value.trim())){
        showError(field, 'Please enter a valid date format.');
        return false;
    } else {
        const splitDate = field.value.split("/");
        const month = splitDate[0];
        const year = splitDate[1];
        const now = new Date();
        const nowMonth = now.getMonth() + 1;
        const nowYear = now.getFullYear() % 100;
        
        if (month < nowMonth && year < nowYear || year < nowYear) {
            showError(field, 'This credit card is expired.');
            return false;
        }
    }
    return true;
}
//Empty Field Validation
function isFieldEmpty(field){
    return field.value.trim() === "";
} 
//CVV Validation
function validateCVVField(field){
    const cvvRegex = /^[0-9]{3}$/;
    if (!cvvRegex.test(field.value.trim())){
        showError(field, 'Please enter a valid CVV number.');
        return false;
    }
    return true;
}
//Name Verification
function validateNameField(field){
    if (isFieldEmpty(field)){
        showError(field, 'Field cannot be empty.');
        return false;
    }
    return true;
}
//Address Verification
function validateAddressField(field){
    const addressRegex = /^\d+\s[A-Za-z\s,.-]+$/;
    if (!addressRegex.test(field.value.trim())){
        showError(field, 'Please provide a valid address.');
        return false;
    }
    return true;
}
//City Verification
function validateCityField(field){
    const cityRegex = /^[0-9]$/;
    if (cityRegex.test(field.value.trim()) || isFieldEmpty(field)){
        showError(field, 'This field cannot be empty or contain numerics.');
        return false;
    }
    return true;
}
//Province Verification (of all provinces in Canada)
function validateProvinceField(field){
    const provinceRegex = /^(AB|BC|MB|NB|NL|NS|NT|NU|ON|PE|QC|SK|YT)$/;
    if (!provinceRegex.test(field.value.trim())){
        showError(field, 'Please provide a valid province in Cannada.');
        return false;
    }
    return true;
}
//Postal Code Verification (A1A 1A1)
function validatePostalField(field){
    const postalRegex = /^[A-Za-z]\d[A-Za-z]-\d[A-Za-z]\d$/;
    if (!postalRegex.test(field.value.trim())){
        showError(field, 'Please provide a valid Canadian postal code.');
        return false;
    }
    return true;
}

// Function responsible for validating the checkout form
function validate() {
    let valid = true;
    // If none of the functions are valid, set valid to false...preventing submission
    if (!(validateCreditCardField(cc) && validateExpirationDateField(exp) 
        && validateCVVField(cvv))) {
        valid = false;
    } 
    //if checkbox is not checked return validation result
    if (!checkbox.checked) {
        return valid
    }
    // If none of the functions are valid, set valid to false...preventing submission
    if (!(validateNameField(nameField) && validateAddressField(address) 
        && validateCityField(city) && validatePostalField(postal) 
        && validateProvinceField(province))){
        valid = false;
    }

    //return validation result
    return valid;
}
