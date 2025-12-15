document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const message = document.getElementById('message').value;
        const topic = document.getElementById('topic').value;

        // **Pengecekan Validasi Sederhana**
        if (topic === "") {
            alert('Mohon pilih Topik terlebih dahulu!');
            return;
        }

        // Ganti dengan nomor WhatsApp tujuan Anda (format internasional tanpa tanda +)
        const whatsappNumber = '6282122339125'; 
        
        // Format pesan untuk WhatsApp (gunakan encodeURIComponent untuk memastikan spasi dan karakter khusus aman)
        const text = `Halo, saya ${name}.%0A%0AEmail: ${email}%0ATopik: ${topic}%0A%0APesan:%0A${message}`;

        // Buka WhatsApp dengan pesan yang telah diformat
        const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${text}`;
        
        // Berikan notifikasi sebelum mengarahkan
        const confirmation = confirm('Anda akan diarahkan ke WhatsApp untuk mengirim pesan. Lanjutkan?');
        
        if (confirmation) {
            window.open(whatsappUrl, '_blank');
        }
    });
});