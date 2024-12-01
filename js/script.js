// Array to store cart items
let cartItems = [];

// Function to update the cart and total price
function updateCart(itemName, price, quantity) {
    const existingItemIndex = cartItems.findIndex(item => item.name === itemName);

    if (existingItemIndex >= 0) {
        // Update quantity if item already exists
        cartItems[existingItemIndex].quantity += quantity;
    } else {
        // Add new item to cart
        cartItems.push({ name: itemName, price: price, quantity: quantity });
    }

    // Update the total price
    updateTotal();
    // Refresh the cart display in the popup
    showCheckoutPopup();
}

// Function to update the total price and display it on the page
function updateTotal() {
    let total = 0;
    cartItems.forEach(item => {
        total += item.price * item.quantity;
    });
    document.getElementById('popup-total').innerText = 'Total: Rp' + total.toLocaleString();
    document.getElementById('total-container').innerText = 'Total: Rp' + total.toLocaleString();
}

// Function to show the checkout popup
function showCheckoutPopup() {
    // Display cart items in the popup
    const popupItems = document.getElementById('popup-items');
    popupItems.innerHTML = ''; // Clear existing items in the popup

    cartItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.innerHTML = `${item.name} x${item.quantity} - Rp${(item.price * item.quantity).toLocaleString()}`;
        popupItems.appendChild(itemElement);
    });

    // Show the popup
    document.getElementById('checkout-popup').style.display = 'flex';
}


// Function to close the checkout popup
function closeCheckoutPopup() {
    document.getElementById('checkout-popup').style.display = 'none';
}

function completeCheckout() {
    // Kumpulkan detail pembayaran
    const items = document.getElementById('popup-items').innerText; 
    const total = document.getElementById('popup-total').innerText;

    // Format pesan untuk dikirim ke admin
    const message = `Pesanan Baru: \nItems: ${items} \nTotal: ${total}`;

    // Tautan WhatsApp dengan pesan yang sudah diisi sebelumnya
    const phoneNumber = '+6282118937714'; // Replace with admin's phone number
    const waLink = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

    // Redirect to WhatsApp
    window.location.href = waLink;
}

// Event listeners for item quantity control buttons
document.querySelectorAll('.btn-plus').forEach((btn, index) => {
    btn.addEventListener('click', () => {
        const itemName = document.querySelectorAll('.menu-item h3')[index].textContent;
        const price = parseInt(document.querySelectorAll('.menu-item .price')[index].textContent.replace('Rp', '').replace('.', ''));
        updateCart(itemName, price, 1);
    });
});

document.querySelectorAll('.btn-minus').forEach((btn, index) => {
    btn.addEventListener('click', () => {
        const itemName = document.querySelectorAll('.menu-item h3')[index].textContent;
        const price = parseInt(document.querySelectorAll('.menu-item .price')[index].textContent.replace('Rp', '').replace('.', ''));
        updateCart(itemName, price, -1); // Decrease quantity
    });
});

