function completeCheckout() {
    const method = document.getElementById('payment-method').value;
    const totalRaw = document.getElementById('popup-total').textContent;
    const totalHarga = totalRaw.replace(/\D/g, ''); 
    const menuItems = document.querySelectorAll('.menu-item');
    
    let detailPesan = "";
    let pesanWA = `*PESANAN BARU - INDO ICE TEA*%0A`;
    
    // ARRAY BARU: Untuk menyimpan data stok yang mau dikirim ke PHP
    let dataStok = [];

    menuItems.forEach(item => {
        const qty = parseInt(item.querySelector('.quantity').textContent);
        if(qty > 0) {
            const namaMenu = item.dataset.name;
            
            // 1. Simpan Text untuk WA & Database Pesanan
            detailPesan += `${namaMenu} (${qty}x), `;
            pesanWA += `• ${namaMenu} (${qty}x)%0A`;

            // 2. Simpan Data Objek untuk Pengurangan Stok
            dataStok.push({
                nama: namaMenu,
                jumlah: qty
            });
        }
    });

    // KIRIM KE DATABASE
    const formData = new FormData();
    formData.append('detail', detailPesan);
    formData.append('total', totalHarga);
    formData.append('metode', method);
    
    // PENTING: Kirim data stok dalam format JSON String
    formData.append('data_stok', JSON.stringify(dataStok));

    fetch('simpan_pesanan.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        console.log("Respon Server:", data); // Cek console kalau ada error
        
        // Buka WhatsApp
        const nomorWA = "6282122339125"; 
        window.open(`https://wa.me/${nomorWA}?text=${pesanWA}%0ATotal: ${totalRaw}%0AMetode: ${method}`, '_blank');
        
        // Reset Tampilan
        const menuItemsReset = document.querySelectorAll('.menu-item');
        menuItemsReset.forEach(i => i.querySelector('.quantity').textContent = '0');
        updateOrderTotal();
        closeCheckoutPopup();
        
        // Reload halaman agar tampilan stok di menu ikut berkurang
        location.reload(); 
    })
    .catch(err => alert("Gagal terhubung ke database"));
}