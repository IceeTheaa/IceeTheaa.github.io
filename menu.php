<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff7e5f;
            --primary-dark: #e66e52;
            --secondary: #feb47b;
            --dark: #2d3436;
            --gray: #636e72;
            --light-bg: #f4f4f9; /* Background sedikit lebih abu agar border putih/item lebih terlihat */
            --navbar-orange: #ff9933; /* Warna orange solid sesuai index lama */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            color: var(--dark);
            scroll-behavior: smooth;
        }

        /* --- Header Orange Solid (Sesuai Index Lama) --- */
        header {
            background-color: var(--navbar-orange); 
            color: white;
            padding: 15px 8%; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            position: sticky; 
            top: 0;
            z-index: 1000;
        }

        header .logo { 
            font-size: 1.6rem; 
            font-weight: 700; 
            color: white; /* Logo jadi putih agar kontras di orange */
            letter-spacing: -0.5px;
        }

        header .nav-links { list-style: none; display: flex; gap: 30px; margin: 0; padding: 0; }
        header .nav-links a { 
            text-decoration: none; 
            color: white; 
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.3s;
            position: relative;
        }
        header .nav-links a::after {
            content: '';
            position: absolute;
            width: 0; height: 2px;
            bottom: -5px; left: 0;
            background: white;
            transition: 0.3s;
        }
        header .nav-links a:hover::after, header .nav-links a.active::after { width: 100%; }
        header .nav-links a:hover, header .nav-links a.active { color: #ffe0b2; }

        /* --- Admin Floating Action Buttons --- */
        .admin-fab-group {
            position: fixed;
            bottom: 30px;
            left: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1001;
        }
        .fab-admin {
            background: var(--dark);
            color: white;
            padding: 14px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .fab-admin.special { background: var(--primary); }
        .fab-admin:hover { transform: scale(1.05) translateY(-5px); box-shadow: 0 12px 30px rgba(255, 126, 95, 0.3); }

        /* --- Menu Section --- */
        #menu { padding: 60px 8%; max-width: 1300px; margin: auto; }
        .menu-title { text-align: center; margin-bottom: 50px; }
        .menu-title h2 { font-weight: 700; font-size: 2.5rem; margin-bottom: 10px; color: var(--dark); }
        .menu-title p { color: var(--gray); font-size: 1rem; }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 35px;
        }

        .menu-item {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            /* Border abu-abu tegas di belakang */
            border: 2px solid #e0e0e0; 
            transition: all 0.4s ease;
            position: relative;
        }

        .menu-item:hover { 
            transform: translateY(-10px); 
            border-color: var(--primary); /* Border berubah warna saat hover */
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
        }

        .menu-img { 
            width: 100%; 
            height: 250px; 
            object-fit: cover; 
            transition: 0.5s;
        }
        .menu-item:hover .menu-img { transform: scale(1.05); }

        .item-details { padding: 25px 25px 15px; }
        .item-details h3 { margin: 0; font-size: 1.35rem; font-weight: 700; color: #333; }
        .item-details p { font-size: 0.9rem; color: var(--gray); margin: 12px 0; line-height: 1.6; height: 45px; overflow: hidden; }

        .price-and-control {
            padding: 0 25px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price { font-weight: 700; color: var(--primary); font-size: 1.4rem; }

        /* --- Quantity Controls --- */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8f8f8;
            padding: 8px 18px;
            border-radius: 50px;
            border: 1px solid #eee;
        }
        .quantity-control button {
            background: white;
            border: 1px solid #ddd;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            color: var(--primary);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .quantity-control button:hover { background: var(--primary); color: white; border-color: var(--primary); }
        .quantity { font-weight: 700; min-width: 25px; text-align: center; color: var(--dark); font-size: 1.1rem; }

        /* --- Admin Controls --- */
        .admin-controls {
            padding: 15px;
            background: #fafafa;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #eee;
        }
        .btn-edit { color: #3498db; text-decoration: none; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }
        .btn-hapus { color: #e74c3c; text-decoration: none; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }

        /* --- Floating Cart --- */
        #floating-cart {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--dark);
            color: white;
            padding: 18px 35px;
            border-radius: 100px;
            display: none;
            align-items: center;
            gap: 25px;
            z-index: 1000;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 300px;
            justify-content: space-between;
        }
        #floating-cart:hover { transform: translateX(-50%) translateY(-5px); background: #000; }
        #cart-item-count { background: var(--primary); color: white; padding: 4px 12px; border-radius: 50px; font-weight: bold; font-size: 0.8rem; }

        /* CSS Popup Checkout (Tetap Bagus) */
        #checkout-popup .popup-content {
            background: #ffffff;
            width: 90%;
            max-width: 420px;
            text-align: center;
            padding: 35px;
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border: none;
            position: relative;
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .popup-title { color: var(--primary); font-weight: 700; font-size: 1.6rem; margin-bottom: 25px; }
        #popup-items { background: #fdfaf9; border: 1px solid #f0e6e4; border-radius: 20px; padding: 15px 20px; margin-bottom: 20px; max-height: 200px; overflow-y: auto; }
        .popup-item-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .total-price-area { font-size: 1.4rem; font-weight: 700; margin: 20px 0; }
        .custom-select { width: 100%; padding: 14px; border-radius: 15px; border: 1px solid #ddd; margin-bottom: 25px; }
        .complete-btn { background: var(--primary); color: white; border: none; padding: 16px; border-radius: 15px; font-weight: 700; width: 100%; cursor: pointer; }
        .close-popup-btn { background: none; border: none; color: #bbb; margin-top: 15px; cursor: pointer; }
    </style>
</head>
<body>

    <?php if(isset($_SESSION['admin'])): ?>
    <div class="admin-fab-group">
        <a href="riwayat_pesanan.php" class="fab-admin special">📥 Pesanan Masuk</a>
        <a href="tambah_menu.php" class="fab-admin">✨ Tambah Menu</a>
        <a href="logout.php" class="fab-admin" style="background: #e74c3c;">🚪 Logout</a>
    </div>
    <?php endif; ?>

    <header>
        <div class="logo">🍹 Indo Ice Tea</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="menu.php" class="active">Menu</a></li>
                <li><a href="contact.php">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <section id="menu">
        <div class="menu-title">
            <h2>Daftar Menu Favorit</h2>
            <p>Pilih minuman segar favoritmu untuk menemani hari-harimu!</p>
        </div>

        <div class="menu-grid">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM menu");
            while($row = mysqli_fetch_array($query)) :
            ?>
            <div class="menu-item" data-name="<?php echo $row['nama_menu']; ?>" data-price="<?php echo $row['harga']; ?>">
                <div style="overflow:hidden">
                    <img src="img/<?php echo $row['gambar']; ?>" alt="Menu" class="menu-img">
                </div>
                <div class="item-details"> 
                    <h3><?php echo $row['nama_menu']; ?></h3>
                    <p><?php echo $row['deskripsi']; ?></p>
                </div>
                <div class="price-and-control">
                    <span class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                    
                    <?php if(!isset($_SESSION['admin'])): ?>
                    <div class="quantity-control">
                        <button class="btn-minus">−</button>
                        <span class="quantity">0</span>
                        <button class="btn-plus">+</button>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(isset($_SESSION['admin'])): ?>
                <div class="admin-controls">
                    <a href="edit_menu.php?id=<?php echo $row['id']; ?>" class="btn-edit">✎ EDIT</a>
                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-hapus" onclick="return confirm('Hapus menu ini?')">🗑 HAPUS</a>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <div id="checkout-popup" class="popup" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter: blur(8px); justify-content:center; align-items:center; z-index:9999; padding: 20px;">
        <div class="popup-content">
            <h3 class="popup-title">Konfirmasi Pesanan</h3>
            <div id="popup-items"></div> 
            <div class="total-price-area">
                Total Akhir: <span id="popup-total" style="color: var(--primary);">Rp 0</span>
            </div>
            <div style="text-align:left;">
                <label style="font-size: 0.8rem; font-weight:600; color:#888;">METODE PEMBAYARAN</label>
                <select id="payment-method" onchange="toggleQrisDisplay()" class="custom-select">
                    <option value="cod">💵 Bayar di Tempat (COD)</option>
                    <option value="qris">📱 QRIS / E-Wallet</option>
                </select>
            </div>
            <div id="qris-area" style="display:none; margin-bottom:25px;">
                <img src="img/qris.jpg" style="width:180px; border-radius: 10px;">
            </div>
            <button onclick="completeCheckout()" class="complete-btn">Pesan Sekarang</button>
            <button onclick="closeCheckoutPopup()" class="close-popup-btn">Tutup & Edit</button>
        </div>
    </div>

    <div id="floating-cart" onclick="showCheckoutPopup()">
        <div style="display:flex; align-items:center; gap:15px;">
            <span id="cart-item-count">0</span>
            <span style="font-size: 1.3rem;">🛒</span>
            <span style="font-weight: 500; font-size: 0.95rem;">Keranjang Belanja</span>
        </div>
        <span id="floating-cart-total" style="font-weight: 700; color: var(--primary);">Rp 0</span>
    </div>

    <script src="js/script.js"></script>
</body>
</html>