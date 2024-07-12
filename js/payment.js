// JavaScript for Payment Form

// Function to show Credit Card form and hide others
function showCreditCardForm() {
    document.getElementById('credit-card-form').style.display = 'block';
    document.getElementById('paypal-form').style.display = 'none';
    document.getElementById('google-pay-form').style.display = 'none';
    document.getElementById('bank-deposit-form').style.display = 'none';
}

// Function to show PayPal form and hide others
function showPayPalForm() {
    document.getElementById('credit-card-form').style.display = 'none';
    document.getElementById('paypal-form').style.display = 'block';
    document.getElementById('google-pay-form').style.display = 'none';
    document.getElementById('bank-deposit-form').style.display = 'none';
}

// Function to show Google Pay form and hide others
function showGooglePayForm() {
    document.getElementById('credit-card-form').style.display = 'none';
    document.getElementById('paypal-form').style.display = 'none';
    document.getElementById('google-pay-form').style.display = 'block';
    document.getElementById('bank-deposit-form').style.display = 'none';
}

function showBankDepositForm() {
    document.getElementById('credit-card-form').style.display = 'none';
    document.getElementById('paypal-form').style.display = 'none';
    document.getElementById('google-pay-form').style.display = 'none';
    document.getElementById('bank-deposit-form').style.display = 'block';
}

// Initial call to show Credit Card form by default
showCreditCardForm();
