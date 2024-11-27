// Code Developed by Gabe

//Initializing constants
const fnameField = document.getElementById('first-name');
const lnameField = document.getElementById('last-name');
const email = document.getElementById('sign-up-email');
const password = document.getElementById('sign-up-password');
const password2 = document.getElementById('sign-up-confirm-password');

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

//function responsible for clearing errors
function clearError(field){
    let errorMessage = document.getElementById(field.id + "-error");
    if (errorMessage){
        field.parentNode.removeChild(errorMessage);//Remove the error message
        field.style.border = ''; // Reset border style
    }
}

//function used to clearing errors on focus (for easier reads)
function onFocused(field){
    clearError(field)
}

//function responsible for checking empty fields validation
function isFieldEmpty(field){
    return field.value.trim() === "";
} 

//Name Verification
function validateNameField(field){
    if (isFieldEmpty(field)){
        showError(field, 'Field cannot be empty.');
        return false;
    }
    return true;
}

//function responsible for email validation
function validateEmailField(field){
    const emailRegex = /^[^\s@]+@[^\s@]+.[^\s@]+$/;
    if (!emailRegex.test(field.value.trim())|| isFieldEmpty(field)) {
        showError(field, 'Please enter a valid email address.');
        console.log("show")
        return false;
    }
    return true;
}

//function responsible for password validation
function validatePasswordField(field){
    if (field.value.length < 8) {
        showError(field, 'Password must be at least 8 characters long.');
        return false;
    }
    return true;
}

//Password match validation
function matchPasswordField(){
    if (password2.value !== password.value || password2.value.trim() === '') {
        showError(password2, 'Passwords do not match.');
        return false;
    }
    return true;
}

//validate function
function validateSignUp(){
    let valid = true;
    //if none of the functions return true set valid to false, preventing form submission
    if (!(validateNameField(fnameField) && validateNameField(lnameField) && 
        validateEmailField(email) && validatePasswordField(password) && 
        matchPasswordField(password2))){
            valid = false;
        }
        // Return validation result
        return valid;
}