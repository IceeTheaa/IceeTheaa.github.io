<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak | Indo Ice Tea</title>
    <link rel="stylesheet" href="css/stylecontact.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">🍹 Indo Ice Tea</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="contact.php" class="active">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <section id="contact-card">
            <h2>Hubungi Kami</h2>
            
            <div class="contact-info-box">
                <p><strong>📍 Alamat:</strong><br>Jl. Sariasih No.22, Sarijadi, Kota Bandung, Indonesia</p>
                <p><strong>⏰ Jam Operasional:</strong><br>Senin - Jumat, 09:00 - 18:00</p>
            </div>
            
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0538645068133!2d107.57685607587421!3d-6.884144367358782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6900f074a3f%3A0x60037a505e0325d!2sJl.%20Sariasih%20No.22%2C%20Sarijadi%2C%20Kec.%20Sukasari%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040151!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <form id="contactForm">
                <label>Informasi Anda</label>
                <div class="input-group">
                    <input type="text" id="name" placeholder="Nama Lengkap" required>
                    <input type="email" id="email" placeholder="Email Anda" required>
                </div>

                <label>Topik & Pesan</label>
                <select id="topic" required>
                    <option value="" disabled selected>Pilih Topik</option>
                    <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                    <option value="Pesanan">Pesanan</option>
                    <option value="Saran & Kritik">Saran & Kritik</option>
                </select>
                <textarea id="message" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                
                <button type="submit" class="btn-send">Kirim Pesan via WhatsApp</button>
            </form>
        </section>
    </div>

    <footer>
        <p>© 2024 Indo Ice Tea. Semua Hak Dilindungi.</p>
    </footer>

    <script src="js/scriptcontact.js"></script>
</body>
</html>