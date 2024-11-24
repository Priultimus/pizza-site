// Function responsible for preventing billing address input if it is the same as delivery address
// If it is checked, you will need to input a billing address
// If it is not checked, it will use your delivery address as your billing address 
function whenClicked(checkbox) {
    console.log("clicked")
    let billingForm = document.querySelector(".billing.form");
    if (checkbox.checked) {
        billingForm.classList.remove('disabled')
    } else {
        billingForm.classList.add('disabled')
    }
}

//Function responsible for displaying error messages and highlighting fields
function showError(input, message) {
    // Create an error message element
    const errorMessage = document.createElement('p');
    errorMessage.className = 'error-message';
    errorMessage.style.color = 'red';
    errorMessage.textContent = message;

    // Append the error message after the input field
    input.style.border = '2px solid red';
    input.parentNode.appendChild(errorMessage);

    //Remove error on click of input field
    input.addEventListener('focus', function(){
        errorMessage.remove(); // Remove the error message
        input.style.border = ''; // Reset border style
    });
}

// Function responsible for validating the checkout form
// function validate() {
//     const cc = document.getElementById('cc');
//     const exp = document.getElementById('exp');
//     const cvv = document.getElementById('cvv');
//     const name = document.getElementById('name');
//     const address = document.getElementById('address');
//     const city = document.getElementById('city');
//     const province = document.getElementById('province');
//     const postal = document.getElementById('postal');

//     let valid = true;

//     //Credit Card Verification
//     const ccRegex = /^[0-9]{16}$/;
//     if (!ccRegex.test(cc.value.trim())){
//         showError(cc, 'Please enter a valid credit card number.');
//         valid = false;
//     }

//     //Expiration Date Verification
//     const expRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
//     if (!expRegex.test(exp.value.trim())){
//         showError(exp, 'Please enter a valid expiration date or adjust format.');
//         valid = false;
//     }

//     //CVV Verification
//     const cvvRegex = /^[0-9]{3}$/;
//     if (!cvvRegex.test(cvv.value.trim())){
//         showError(cvv, 'Please enter a valid CVV number.');
//         valid = false;
//     }

//     //Name Verification
//     const nameValue = name.value.trim();
//     if (nameValue === ""){
//         showError(name, 'Please provide your name.');
//         valid = false;
//     }

//     //Address Verification
//     const addressRegex = /^\d+\s[A-Za-z\s,.-]+$/;
//     if (!addressRegex.test(address.value.trim())){
//         showError(address, 'Please provide a valid address.');
//         valid = false;
//     }

//     //City Verification
//     const cityRegex = /^[A-Za-z]([\s'-][A-Za-z]+)*+$/;
//     if (!cityRegex.test(city.value.trim())){
//         showError(city, 'Please provide a valid city');
//         valid = false;
//     }

//     //Province Verification (of all provinces in Canada)
//     const provinceRegex = /^(AB|BC|MB|NB|NL|NS|NT|NU|ON|PE|QC|SK|YT)$/;
//     if (!provinceRegex.test(province.value.trim())){
//         showError(province, 'Please provide a valid province in Cannada');
//         valid = false;
//     }

//     //Postal Code Verification (A1A 1A1)
//     const postalRegex = /^[A-Za-z]\d[A-Za-z]-\d[A-Za-z]\d$/;
//     if (!postalRegex.test(postal.value.trim())){
//         showError(postal, 'Please provide a valid province in Canada');
//         valid = false;
//     }

//     //Prevent payment if validation fails
//     if (!valid) {
//         return false;
//     }

//     //Allow form submission
//     return true;
// }
