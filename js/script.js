// Menangani perubahan jumlah item
const updateQuantity = () => {
    const quantityControls = document.querySelectorAll('.quantity-control');

    quantityControls.forEach(control => {
        const btnMinus = control.querySelector('.btn-minus');
        const btnPlus = control.querySelector('.btn-plus');
        const quantity = control.querySelector('.quantity');

        // Penanganan tombol minus
        btnMinus.addEventListener('click', () => {
            let currentQuantity = parseInt(quantity.textContent);
            if (currentQuantity > 0) {
                quantity.textContent = currentQuantity - 1;
            }
            updateTotal();
        });

        // Penanganan tombol plus
        btnPlus.addEventListener('click', () => {
            let currentQuantity = parseInt(quantity.textContent);
            quantity.textContent = currentQuantity + 1;
            updateTotal();
        });
    });
};

// Update total harga
function updateTotal() {
    let total = 0;
    const items = document.querySelectorAll('.menu-item');
    items.forEach(item => {
        const quantity = parseInt(item.querySelector('.quantity').textContent);
        const price = parseInt(item.querySelector('span').textContent.replace('Rp', '').replace('.', ''));
        total += quantity * price;
    });
    document.getElementById('total-container').textContent = `Total: Rp${total.toLocaleString()}`;
    document.getElementById('popup-total').textContent = `Total: Rp${total.toLocaleString()}`;
}

// Memanggil fungsi updateQuantity saat halaman selesai dimuat
window.addEventListener('DOMContentLoaded', (event) => {
    updateQuantity();
});


// Update total and show checkout pop-up
function updateTotal() {
    let total = 0;
    const items = document.querySelectorAll('.menu-item');
    items.forEach(item => {
        const quantity = item.querySelector('.quantity').textContent;
        const price = parseInt(item.querySelector('span').textContent.replace('Rp', '').replace('.', ''));
        total += quantity * price;
    });
    document.getElementById('total-container').textContent = `Total: Rp${total.toLocaleString()}`;
    document.getElementById('popup-total').textContent = `Total: Rp${total.toLocaleString()}`;
}

// Show checkout pop-up
function showCheckoutPopup() {
    updateTotal();
    document.getElementById('checkout-popup').style.display = 'flex';
}

// Close checkout pop-up
function closeCheckoutPopup() {
    document.getElementById('checkout-popup').style.display = 'none';
}

// Fungsi untuk mengirim pesanan (misalnya hanya menutup pop-up untuk demonstrasi)
function submitOrder() {
    alert('Pesanan Anda telah dikirim!');
    closeCheckoutPopup(); 
}

function updatePrice(selectElement) {
    const priceElement = selectElement.parentElement.querySelector('.price');
    const selectedSize = selectElement.value;
    const smallPrice = parseInt(priceElement.getAttribute('data-small'));
    const mediumPrice = parseInt(priceElement.getAttribute('data-medium'));

    if (selectedSize === 'small') {
        priceElement.innerText = 'Rp' + smallPrice.toLocaleString();
    } else {
        priceElement.innerText = 'Rp' + mediumPrice.toLocaleString();
    }
}
