function whenClicked(checkbox) {
    console.log("clicked")
    let billingForm = document.querySelector(".billing.form");
    if (checkbox.checked) {
        billingForm.classList.remove('disabled')
    } else {
        billingForm.classList.add('disabled')
    }
}