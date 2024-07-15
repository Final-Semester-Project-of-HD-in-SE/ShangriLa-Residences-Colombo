// Select input elements for the card number, card holder name, expiration month and year, and CVV from the document.
let cardNo = document.querySelector('.card-number-input');
let cardBox = document.querySelector('.card-number-box');
let cardHolder = document.querySelector('.card-holder-input');
let cardHolderName = document.querySelector('.card-holder-name');
let EmonthInput = document.querySelector('.month-input');
let EyearInput = document.querySelector('.year-input');
let Emonth = document.querySelector('.exp-month');
let Eyear = document.querySelector('.exp-year');
let cvvInput = document.querySelector('.cvv-input');
// Select the front and back sides of the card visualization.
let front = document.querySelector('.front');
let back = document.querySelector('.back');


// Event listener for card number input. Updates the text in the card visualization as the user types.
cardNo.addEventListener('input', () => {
  // Retrieve the input value from the card number input field
  let inputValue = cardNo.value.trim();
  
  // Remove any non-numeric characters from the input value
  let numericValue = inputValue.replace(/\D/g, '');
  
  // Update the card number input field value with the sanitized numeric value
  cardNo.value = numericValue;
  
  // Update the card box visualization with the sanitized numeric value
  cardBox.innerText = numericValue;
});

// Event listener for CVV input. Updates the text in the card visualization as the user types.
cvvInput.addEventListener('input', () => {
  // Retrieve the input value from the CVV input field
  let inputValue = cvvInput.value.trim();
  
  // Remove any non-numeric characters from the input value
  let numericValue = inputValue.replace(/\D/g, '');
  
  // Update the CVV input field value with the sanitized numeric value
  cvvInput.value = numericValue;
});

// Event listener for card number input. Updates the text in the card visualization as the user types.
cardNo.oninput = () => {
    cardBox.innerText = cardNo.value;
};

// Event listener for card holder name input. Updates the card holder name in the card visualization as the user types.
cardHolder.oninput = () => {
    cardHolderName.innerText = cardHolder.value;
};

// Event listener for expiration month selection. Updates the expiration month in the card visualization when the user selects a month.
EmonthInput.oninput = () => {
    Emonth.innerHTML = EmonthInput.value;
};

// Event listener for expiration year selection. Updates the expiration year in the card visualization when the user selects a year.
EyearInput.oninput = () => {
    Eyear.innerHTML = EyearInput.value;
};


// Event listener triggered when the mouse enters the CVV input field
cvvInput.onmousecenter = () => {
    // Applies a 3D rotation to the front side of the card, making it face away.
    front.style.transform = 'perspective(1000px) rotateY(180deg)';
    // Resets any 3D rotation applied to the back side of the card, making it face forward.
    back.style.transform = 'perspective(1000px) rotateY(0deg)';
};


// Define a function to parse URL query parameters.
const getQueryParams = () => {
  // Create an instance of URLSearchParams to easily work with query parameters.
  const params = new URLSearchParams(window.location.search);
  try {
      // Attempt to parse the 'items' query parameter as JSON. If not present, default to an empty array.
      // Also, retrieve the 'total' query parameter, defaulting to '0' if not present.
      return {
          items: JSON.parse(decodeURIComponent(params.get('items')) || '[]'),
          total: params.get('total')
      };
  } catch (error) {
      // Log any errors encountered during parsing.
      console.error('Error parsing query parameters:', error);
      // Return a default object structure in case of error.
      return { items: [], total: '0' };
  }
};

// Define a function to generate the HTML content for the receipt based on order details.
const generateReceiptHTML = (orderDetails) => {
  // Map over each item in the order details to create a string of HTML for each.
  let receiptHTML = orderDetails.items.map(item => `
      <div class="receipt-item" style="display: flex; align-items: center; margin-bottom: 20px;">
          <div class="receipt-image" style="flex-shrink: 0;">
              <img src="${item.image}" alt="${item.title}" style="width: 100px; height: 100px; object-fit: contain;">
          </div>
          <div class="receipt-details" style="margin-left: 20px;">
              <strong>${item.title}</strong><br>
              Quantity: ${item.quantity}<br>
              Price: $${item.price.toFixed(2)}<br>
              Subtotal: $${(item.price * item.quantity).toFixed(2)}
          </div>
      </div>
  `).join(''); // Join all item HTML strings into a single string.

  // Append a total price line to the receipt HTML.
  receiptHTML += `<div style="margin-top: 20px;"><strong>Total: $${parseFloat(orderDetails.total).toFixed(2)}</strong></div>`;
  // Return the complete receipt HTML.
  return receiptHTML;
};

// Get the order details from the query parameters.
const orderDetails = getQueryParams();
// Log the order details to the console for debugging.
console.log('Order Details:', orderDetails);

// Select the receipt container element.
const receiptContainer = document.querySelector('.receipt-container');
// Check if the receipt container exists.
if (receiptContainer) {
  // Set the inner HTML of the receipt container to the generated receipt HTML.
  receiptContainer.innerHTML = generateReceiptHTML(orderDetails);
} else {
  // Log an error if the receipt container was not found.
  console.error('Receipt container not found');
}

// Function to handle form submission
const handleFormSubmission = (event) => {
  // Prevent default form submission behavior
  event.preventDefault();
  
  // Simulate payment processing (replace this with your actual payment processing logic)
  const paymentSuccessful = true; // Assuming payment is successful
  
  if (paymentSuccessful) {
      // Show payment successful message
      alert("Payment successful");
      
      // Redirect to home.html after a short delay (e.g., 2 seconds)
      setTimeout(() => {
          window.location.href = "user-dash.html";

      }, 2000);
  } else {
      // Handle payment failure (optional)
      alert("Payment failed. Please try again.");
  }
};

// Select the form element
const paymentForm = document.querySelector('form');

// Add event listener to the form for submission
if (paymentForm) {
  paymentForm.addEventListener('submit', handleFormSubmission);
} else {
  console.error('Payment form not found');
}

