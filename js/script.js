// 1. Fungsi format mata uang
function formatRupiah(angka) {
    const reverse = angka.toString().split('').reverse().join('');
    const ribuan = reverse.match(/\d{1,3}/g);
    const result = ribuan.join('.').split('').reverse().join('');
    return 'Rp' + result;
}

// 2. Fungsi untuk menampilkan/menyembunyikan QRIS (PENTING: Harus di luar DOMContentLoaded)
function toggleQrisDisplay() {
    const method = document.getElementById('payment-method').value;
    const qrisArea = document.getElementById('qris-area');
    if (qrisArea) {
        qrisArea.style.display = (method === 'qris') ? 'block' : 'none';
    }
}

// 3. Fungsi update total keranjang
function updateOrderTotal() {
    let total = 0;
    let totalItems = 0;
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        const quantityElement = item.querySelector('.quantity');
        const quantity = parseInt(quantityElement.textContent);
        const price = parseInt(item.dataset.price); 
        
        total += quantity * price;
        totalItems += quantity;
    });
    
    document.getElementById('floating-cart-total').textContent = `Total: ${formatRupiah(total)}`;
    document.getElementById('cart-item-count').textContent = totalItems;

    const floatingCart = document.getElementById('floating-cart');
    if (floatingCart) {
        floatingCart.style.display = (totalItems > 0) ? 'flex' : 'none';
    }
    return total;
}

// 4. Inisialisasi Tombol
document.addEventListener('DOMContentLoaded', () => {
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        const btnMinus = item.querySelector('.btn-minus');
        const btnPlus = item.querySelector('.btn-plus');
        const quantityElement = item.querySelector('.quantity');

        if(btnPlus) {
            btnPlus.addEventListener('click', () => {
                quantityElement.textContent = parseInt(quantityElement.textContent) + 1;
                updateOrderTotal();
            });
        }
        if(btnMinus) {
            btnMinus.addEventListener('click', () => {
                let qty = parseInt(quantityElement.textContent);
                if (qty > 0) {
                    quantityElement.textContent = qty - 1;
                    updateOrderTotal();
                }
            });
        }
    });
});

// 5. Fungsi Munculkan Popup
function showCheckoutPopup() {
    const popup = document.getElementById('checkout-popup');
    const popupItemsContainer = document.getElementById('popup-items');
    const popupTotalElement = document.getElementById('popup-total');
    
    let total = 0;
    let orderListHTML = '';
    const menuItems = document.querySelectorAll('.menu-item');

    menuItems.forEach(item => {
        const quantity = parseInt(item.querySelector('.quantity').textContent);
        if (quantity > 0) {
            const name = item.dataset.name;
            const price = parseInt(item.dataset.price);
            const subtotal = quantity * price;

            orderListHTML += `<div style="display:flex; justify-content:space-between;">
                                <span>${quantity}x ${name}</span>
                                <span>${formatRupiah(subtotal)}</span>
                              </div>`;
            total += subtotal;
        }
    });

    if (total === 0) return alert('Keranjang kosong!');

    popupItemsContainer.innerHTML = orderListHTML;
    popupTotalElement.textContent = `Total Akhir: ${formatRupiah(total)}`;
    popup.style.display = 'flex';
}

function closeCheckoutPopup() {
    document.getElementById('checkout-popup').style.display = 'none';
}

// 6. Fungsi Selesaikan Pesanan (Simpan ke DB lalu WA)
function completeCheckout() {
    const method = document.getElementById('payment-method').value;
    const totalRaw = document.getElementById('popup-total').textContent;
    const totalHarga = totalRaw.replace(/\D/g, ''); 
    const menuItems = document.querySelectorAll('.menu-item');
    
    let detailPesan = "";
    let pesanWA = `*PESANAN BARU - INDO ICE TEA*%0A`;
    
    menuItems.forEach(item => {
        const qty = parseInt(item.querySelector('.quantity').textContent);
        if(qty > 0) {
            detailPesan += `${item.dataset.name} (${qty}x), `;
            pesanWA += `• ${item.dataset.name} (${qty}x)%0A`;
        }
    });

    // KIRIM KE DATABASE
    const formData = new FormData();
    formData.append('detail', detailPesan);
    formData.append('total', totalHarga);
    formData.append('metode', method);

    fetch('simpan_pesanan.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        // Jika berhasil simpan, baru buka WA
        const nomorWA = "6282122339125"; 
        window.open(`https://wa.me/${nomorWA}?text=${pesanWA}%0ATotal: ${totalRaw}%0AMetode: ${method}`, '_blank');
        
        // Reset
        const menuItemsReset = document.querySelectorAll('.menu-item');
        menuItemsReset.forEach(i => i.querySelector('.quantity').textContent = '0');
        updateOrderTotal();
        closeCheckoutPopup();
    })
    .catch(err => alert("Gagal terhubung ke database"));
}