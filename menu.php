<?php
require 'config/db.php';
$menus = mysqli_query($conn, "SELECT * FROM menu");
?>

<?php if($_SESSION['role']=='admin'): ?>
<a href="admin/add_menu.php">➕ Tambah Menu</a>
<?php endif; ?>


<?php
session_start();

// proteksi halaman menu
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Indo Ice Tea</title>

    <link rel="stylesheet" href="css/stylemenu.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="image.png">
</head>
<body>

<header>
    <div class="logo">🍹 Indo Ice Tea</div>
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="menu.php" class="active">Menu</a></li>
            <li><a href="contact.html">Kontak</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section id="menu">
    <h2>Menu Kami</h2>

    <div class="menu-grid">

<?php while($row = mysqli_fetch_assoc($menus)) : ?>
<div class="menu-item" 
     data-name="<?= $row['nama'] ?>" 
     data-price="<?= $row['harga'] ?>">

    <img src="img/<?= $row['gambar'] ?>" class="menu-img">

    <div class="item-details">
        <h3><?= $row['nama'] ?></h3>
        <p><?= $row['deskripsi'] ?></p>
    </div>

    <div class="price-and-control">
        <span class="price">Rp<?= number_format($row['harga']) ?></span>

        <div class="quantity-control">
            <button class="btn-minus">-</button>
            <span class="quantity">0</span>
            <button class="btn-plus">+</button>
        </div>

        <?php if($_SESSION['role'] == 'admin'): ?>
        <div class="admin-control">
            <a href="admin/edit_menu.php?id=<?= $row['id'] ?>">✏️</a>
            <a href="admin/delete_menu.php?id=<?= $row['id'] ?>" 
               onclick="return confirm('Hapus menu?')">🗑️</a>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php endwhile; ?>

</div>


    <div class="content-padding-bottom"></div>
</section>

<!-- POPUP CHECKOUT -->
<div id="checkout-popup" class="popup">
    <div class="popup-content">
        <h3>Ringkasan Pesanan</h3>
        <div id="popup-items"></div>
        <p id="popup-total">Total: Rp0</p>
        <button onclick="completeCheckout()" class="btn-checkout">Selesaikan Pesanan</button>
        <button onclick="closeCheckoutPopup()" class="btn-close">Tutup</button>
    </div>
</div>

<div id="floating-cart" onclick="showCheckoutPopup()">
    <span id="cart-item-count" class="cart-badge">0</span>
    <span class="cart-icon">🛒</span>
    <span id="floating-cart-total" class="cart-total-price">Total: Rp0</span>
</div>

<footer>
    <p>2024 Indo Ice Tea. Semua Hak Dilindungi.</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>
