/**
 * File: js/script.js
 * Deskripsi: Mengelola fungsionalitas keranjang belanja, kuantitas, perhitungan total, dan popup checkout.
 */

// 1. Fungsi untuk memformat angka menjadi mata uang Rupiah
function formatRupiah(angka) {
    const reverse = angka.toString().split('').reverse().join('');
    const ribuan = reverse.match(/\d{1,3}/g);
    const result = ribuan.join('.').split('').reverse().join('');
    return 'Rp' + result;
}

// 2. Fungsi untuk menghitung dan memperbarui total (HARGA dan JUMLAH ITEM)
function updateOrderTotal() {
    let total = 0;
    let totalItems = 0; // Menghitung total item
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        const quantityElement = item.querySelector('.quantity');
        const quantity = parseInt(quantityElement.textContent);
        const price = parseInt(item.dataset.price); 
        
        total += quantity * price;
        totalItems += quantity; // Menambahkan kuantitas ke total item
    });
    
    // Perbarui tampilan total harga di Floating Cart
    document.getElementById('floating-cart-total').textContent = `Total: ${formatRupiah(total)}`;
    
    // Perbarui jumlah item di badge Floating Cart
    document.getElementById('cart-item-count').textContent = totalItems;

    // Logika untuk menyembunyikan/menampilkan keranjang jika kosong
    const floatingCart = document.getElementById('floating-cart');
    if (totalItems > 0) {
        // Tampilkan sebagai 'flex' (agar muncul)
        floatingCart.style.display = 'flex'; 
    } else {
        // Sembunyikan jika 0 item
        floatingCart.style.display = 'none'; 
    }

    return total;
}

// 3. Fungsi untuk me-reset semua kuantitas item menjadi 0
function resetQuantities() {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        const quantityElement = item.querySelector('.quantity');
        quantityElement.textContent = '0';
    });
    updateOrderTotal(); 
}


// 4. Menghubungkan fungsi ke tombol + dan - (Dijalankan saat DOM siap)
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        const btnMinus = item.querySelector('.btn-minus');
        const btnPlus = item.querySelector('.btn-plus');
        const quantityElement = item.querySelector('.quantity');

        btnPlus.addEventListener('click', () => {
            let quantity = parseInt(quantityElement.textContent);
            quantity += 1;
            quantityElement.textContent = quantity;
            updateOrderTotal();
        });

        btnMinus.addEventListener('click', () => {
            let quantity = parseInt(quantityElement.textContent);
            if (quantity > 0) {
                quantity -= 1;
                quantityElement.textContent = quantity;
                updateOrderTotal();
            }
        });
    });

    // Panggil updateOrderTotal saat halaman pertama kali dimuat
    updateOrderTotal(); 
});


// 5. Fungsi Popup Checkout
function showCheckoutPopup() {
    const popup = document.getElementById('checkout-popup');
    const popupItemsContainer = document.getElementById('popup-items');
    const popupTotalElement = document.getElementById('popup-total');
    
    let total = 0;
    let orderListHTML = '';
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        const quantityElement = item.querySelector('.quantity');
        const quantity = parseInt(quantityElement.textContent);
        
        if (quantity > 0) {
            const name = item.dataset.name;
            const price = parseInt(item.dataset.price);
            const subtotal = quantity * price;

            // Markup Baru untuk tampilan yang lebih rapi
            orderListHTML += `
                <p>
                    <span>${quantity}x ${name}</span>
                    <strong>${formatRupiah(subtotal)}</strong>
                </p>`;
            total += subtotal;
        }
    });

    if (total === 0) {
        alert('Keranjang belanja masih kosong! Silakan pilih menu.');
        return;
    }

    popupItemsContainer.innerHTML = orderListHTML;
    popupTotalElement.textContent = `Total Akhir: ${formatRupiah(total)}`;
    popup.style.display = 'flex';
}

function closeCheckoutPopup() {
    document.getElementById('checkout-popup').style.display = 'none';
}

function completeCheckout() {
    alert('Pesanan Anda berhasil dikirim! Keranjang belanja di-reset.');
    
    // Reset keranjang setelah checkout selesai
    resetQuantities();
    
    closeCheckoutPopup();
}