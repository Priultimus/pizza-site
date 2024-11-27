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

// Function to clear error message and reset the field's border style.
function clearError(field){
    // Get the error message element related to the input field by appending '-error' to the field's ID.
    let errorMessage = document.getElementById(field.id + "-error");
     // Check if the error message exists for the field.
    if (errorMessage){
        // Remove the error message element from the DOM if it exists.
        field.parentNode.removeChild(errorMessage);
        // Reset border style
        field.style.border = ''; 
    }
}

// Function to handle the 'focus' event on an input field.
function onFocused(field){
    // Call the clearError function to remove any error message and reset the field's border style.
    clearError(field)
}

// Credit Card Number Validation
function validateCreditCardField(field) {
    // Regex to check for a valid 16-digit credit card number.
    const ccRegex = /^[0-9]{16}$/;
    
    // Test the field value with the regex and show error if invalid.
    if (!ccRegex.test(field.value.trim())) {
        showError(field, 'Please enter a valid 16-digit credit card number.');
        return false;
    }
    return true;
}

// Expiration Date Validation
function validateExpirationDateField(field) {
    // Regex to check for a valid expiration date format (MM/YY).
    const expRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;

    // Test the field value with the regex and show error if invalid.
    if (!expRegex.test(field.value.trim())) {
        showError(field, 'Please enter a valid date format.');
        return false;
    } else {
        // Split the expiration date into month and year.
        const splitDate = field.value.split("/");
        const month = splitDate[0];
        const year = splitDate[1];
        const now = new Date();
        const nowMonth = now.getMonth() + 1;
        const nowYear = now.getFullYear() % 100;

        // Check if the card is expired based on current month and year.
        if (month < nowMonth && year < nowYear || year < nowYear) {
            showError(field, 'This credit card is expired.');
            return false;
        }
    }
    return true;
}

// Check if a field is empty
function isFieldEmpty(field) {
    return field.value.trim() === ""; // Return true if the field is empty.
}

// CVV Validation (3-digit security code)
function validateCVVField(field) {
    // Regex to check for a valid 3-digit CVV number.
    const cvvRegex = /^[0-9]{3}$/;
    if (!cvvRegex.test(field.value.trim())) {
        showError(field, 'Please enter a valid CVV number.');
        return false;
    }
    return true;
}

// Name Field Validation (non-empty check)
function validateNameField(field) {
    if (isFieldEmpty(field)) {
        showError(field, 'Field cannot be empty.');
        return false;
    }
    return true;
}

// Address Validation (basic address format)
function validateAddressField(field) {
    // Regex to check for a valid address format (e.g., "123 Main St").
    const addressRegex = /^\d+\s[A-Za-z\s,.-]+$/;
    if (!addressRegex.test(field.value.trim())) {
        showError(field, 'Please provide a valid address.');
        return false;
    }
    return true;
}

// City Field Validation (should not contain numbers)
function validateCityField(field) {
    // Regex to check for valid city name (cannot contain numbers).
    const cityRegex = /^[0-9]$/;
    if (cityRegex.test(field.value.trim()) || isFieldEmpty(field)) {
        showError(field, 'This field cannot be empty or contain numerics.');
        return false;
    }
    return true;
}

// Province Field Validation (for Canadian provinces)
function validateProvinceField(field) {
    // Regex to check for valid Canadian province abbreviation (e.g., "ON" for Ontario).
    const provinceRegex = /^(AB|BC|MB|NB|NL|NS|NT|NU|ON|PE|QC|SK|YT)$/;
    if (!provinceRegex.test(field.value.trim())) {
        showError(field, 'Please provide a valid province in Canada.');
        return false;
    }
    return true;
}

// Postal Code Validation (Canadian format "A1A-1A1")
function validatePostalField(field) {
    // Regex to check for a valid Canadian postal code format.
    const postalRegex = /^[A-Za-z]\d[A-Za-z]-\d[A-Za-z]\d$/;
    if (!postalRegex.test(field.value.trim())) {
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




