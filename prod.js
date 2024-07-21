// JavaScript file

// Array of products
const product = [
    {
        id: 0,
        image: 'pic2.jpg',

        title: 'Monthly Maintenance Fee',
        price: 263.00,
    },
    {
        id: 1,
        image: 'pic1.jpg',
        title: 'Annual Security Fee',
        price: 500.00,
    }
];

// Array to store items in cart
let cart = [];

// Divide products into categories
const categories = {
    "Monthly Maintenance Fee": product.slice(0, 1),
    "Annual Security Fee": product.slice(1, 2)
};

// Display products categorized
document.getElementById('root').innerHTML = Object.entries(categories).map(([category, products]) => {
    const productsHTML = products.map((item) => {
        return (
            `<div class='box'>
                <div class='img-box'>
                    <img class='images' src='${item.image}'></img>
                </div>
                <div class='bottom'>
                    <p>${item.title}</p>
                    <h4>$ ${item.price.toFixed(2)}</h4>
                    <button onclick='addtocart(${item.id})'>Add to list</button>
                </div>
            </div>`
        );
    }).join('');

    return (
        `<div>
            <h2>${category}</h2>
            <div class="container2">${productsHTML}</div>
        </div>`
    );
}).join('');

// Add item to cart
function addtocart(index) {
    cart.push({...product[index]});
    displaycart();
}

// Delete item from cart
function delElement(index) {
    cart.splice(index, 1);
    displaycart();
}

// Display items in cart
function displaycart() {
    let total = 0;
    const cartItems = cart.map((item, index) => {
        total += item.price;
        return (
            `<div class='cart-item'>
                <div class='row-img'>
                    <img class='rowimg' src='${item.image}'>
                </div>
                <p style='font-size:12px;'>${item.title}</p>
                <h2 style='font-size: 15px;'>$ ${item.price.toFixed(2)}</h2>
                <i class='bx bx-trash' onclick='delElement(${index})'></i>
            </div>`
        );
    }).join('');

    document.getElementById('cartItem').innerHTML = cartItems || "Your payment is empty";
    document.getElementById("total").innerHTML = "$ " + total.toFixed(2);
}

// Clear the cart
document.getElementById("clearOrderBtn").addEventListener("click", function() {
    cart = [];
    displaycart();
});

// Form submission
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const address = document.getElementById('address').value.trim();

    const nameContainsOnlyLetters = /^[A-Za-z\s]+$/.test(name);
    if (!nameContainsOnlyLetters) {
        alert('Please enter a valid name without numbers.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    if (!name || !email || !address) {
        alert('Please fill in all fields.');
        return;
    }

    let orderSummary = `Dear ${name}, your payment:\n`;

    cart.forEach(item => {
        orderSummary += `- ${item.title} for $${item.price.toFixed(2)}\n`;
    });

    const total = cart.reduce((acc, item) => acc + item.price, 0);
    orderSummary += `Your total payment is $${total.toFixed(2)}`;

    alert(orderSummary);

    displaycart();

    document.querySelector('form').reset();

    // Convert cart array to JSON string and encode it
    const cartData = encodeURIComponent(JSON.stringify(cart));
    const totalData = encodeURIComponent(total.toFixed(2));

    // Redirect to Card.php with query parameters
    window.location.href = `Card.php?items=${cartData}&total=${totalData}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&address=${encodeURIComponent(address)}`;
});
