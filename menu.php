<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Indo Ice Tea</title>
    <link rel="stylesheet" href="css/stylemenu.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff7e5f;
            --primary-light: ##ff7e5f;
            --dark: #2d3436;
            --gray: #636e72;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fffaf9;
            margin: 0;
            color: var(--dark);
        }

        /* Header Modern */
        header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(255, 126, 95, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo { font-size: 1.5rem; font-weight: 700; color: var(--primary); }

        .nav-links { list-style: none; display: flex; gap: 25px; margin: 0; padding: 0; }
        .nav-links a { 
            text-decoration: none; 
            color: var(--dark); 
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); }

        /* Admin Action Buttons (Ganti Banner) */
        .admin-fab-group {
            position: fixed;
            bottom: 30px;
            left: 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 1001;
        }
        .fab-admin {
            background: var(--dark);
            color: white;
            padding: 12px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .fab-admin.special { background: var(--primary); }
        .fab-admin:hover { transform: translateY(-5px); filter: brightness(1.1); }

        /* Section Menu */
        #menu { padding: 40px 5%; max-width: 1200px; margin: auto; }
        h2 { text-align: center; margin-bottom: 40px; font-weight: 700; font-size: 2rem; color: var(--dark); }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .menu-item {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #fdf0ed;
        }

        .menu-item:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(255, 126, 95, 0.2); }
        .menu-img { width: 100%; height: 220px; object-fit: cover; }

        .item-details { padding: 20px 20px 10px; }
        .item-details h3 { margin: 0; font-size: 1.25rem; font-weight: 700; }
        .item-details p { font-size: 0.85rem; color: var(--gray); margin: 8px 0; line-height: 1.6; }

        .price-and-control {
            padding: 0 20px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price { font-weight: 700; color: var(--primary); font-size: 1.2rem; }

        /* Quantity Controls */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--primary-light);
            padding: 6px 14px;
            border-radius: 50px;
        }
        .quantity-control button {
            background: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            color: var(--primary);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: 0.2s;
        }
        .quantity-control button:hover { background: var(--primary); color: white; }
        .quantity { font-weight: 700; min-width: 20px; text-align: center; color: var(--primary); }

        /* Admin Item Controls */
        .admin-controls {
            padding: 15px;
            background: #fafafa;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #eee;
        }
        .btn-edit { color: #3498db; text-decoration: none; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px; }
        .btn-hapus { color: #e74c3c; text-decoration: none; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px; }

        /* Floating Cart (Pindah ke Tengah Bawah) */
        #floating-cart {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--dark);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            display: none;
            align-items: center;
            gap: 20px;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: 0.3s;
            min-width: 250px;
            justify-content: space-between;
        }
        #floating-cart:hover { bottom: 35px; background: #000; }
        #cart-item-count { background: var(--primary); color: white; padding: 2px 10px; border-radius: 10px; font-weight: bold; }

        /* Popups */
        .popup-content {
            border-radius: 30px !important;
            padding: 35px !important;
            border: none !important;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2) !important;
        }
        .complete-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }
        .complete-btn:hover { background: #e66e52; }
    </style>
</head>
<body>

    <?php if(isset($_SESSION['admin'])): ?>
    <div class="admin-fab-group">
        <a href="riwayat_pesanan.php" class="fab-admin special">📥 Pesanan Masuk</a>
        <a href="tambah_menu.php" class="fab-admin">+ Tambah Menu</a>
        <a href="logout.php" class="fab-admin" style="background: #e74c3c;">Logout</a>
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
        <h2>Menu Favorit</h2>
        <div class="menu-grid">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM menu");
            while($row = mysqli_fetch_array($query)) :
            ?>
            <div class="menu-item" data-name="<?php echo $row['nama_menu']; ?>" data-price="<?php echo $row['harga']; ?>">
                <img src="img/<?php echo $row['gambar']; ?>" alt="Menu" class="menu-img">
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
                    <a href="edit_menu.php?id=<?php echo $row['id']; ?>" class="btn-edit">EDIT</a>
                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-hapus" onclick="return confirm('Hapus menu ini?')">HAPUS</a>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <div id="checkout-popup" class="popup" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter: blur(5px); justify-content:center; align-items:center; z-index:9999;">
        <div class="popup-content" style="background:white; width:90%; max-width:400px; text-align:center;">
            <h3 style="margin-bottom:20px; color:var(--primary); font-size: 1.5rem;">Ringkasan Pesanan</h3>
            <div id="popup-items" style="max-height:200px; overflow-y:auto; margin-bottom:20px; text-align:left; background: #f9f9f9; padding: 15px; border-radius: 15px;"></div> 
            <p id="popup-total" style="font-weight:700; font-size:1.3rem; margin-bottom: 20px;">Total: Rp 0</p>

            <div style="margin-bottom:20px; text-align:left;">
                <label style="font-size:0.85rem; font-weight:600; color: #666;">Metode Pembayaran:</label>
                <select id="payment-method" onchange="toggleQrisDisplay()" style="width:100%; padding:12px; border-radius:12px; border: 1px solid #ddd; margin-top: 8px; font-family: 'Poppins';">
                    <option value="cod">💵 Bayar di Tempat (COD)</option>
                    <option value="qris">📱 QRIS / E-Wallet</option>
                </select>
            </div>

            <div id="qris-area" style="display:none; margin-bottom:20px; padding: 15px; border: 1px dashed var(--primary); border-radius: 15px;">
                <p style="font-size:0.7rem; color:gray; margin-bottom: 10px;">Scan QRIS Indo Ice Tea:</p>
                <img src="img/qris.jpg" style="width:160px; border-radius: 10px;">
            </div>

            <button onclick="completeCheckout()" class="complete-btn">Selesaikan Pesanan</button>
            <button onclick="closeCheckoutPopup()" style="background:none; border:none; color:#bbb; margin-top:15px; cursor:pointer; font-weight:600;">Kembali</button>
        </div>
    </div>

    <div id="floating-cart" onclick="showCheckoutPopup()">
        <div style="display:flex; align-items:center; gap:12px;">
            <span id="cart-item-count">0</span>
            <span style="font-size: 1.2rem;">🛒</span>
            <span style="font-weight: 600;">Lihat Keranjang</span>
        </div>
        <span id="floating-cart-total">Rp 0</span>
    </div>

    <script src="js/script.js"></script>
</body>
</html>