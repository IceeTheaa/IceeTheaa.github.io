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