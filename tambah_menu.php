<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $harga = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Upload Gambar
    $filename = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $folder = "img/" . $filename;

    if (move_uploaded_file($tmp_name, $folder)) {
        mysqli_query($conn, "INSERT INTO menu (nama_menu, harga, deskripsi, gambar) VALUES ('$nama', '$harga', '$deskripsi', '$filename')");
        header("Location: menu.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Menu | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 50px; }
        .card { background: white; padding: 30px; border-radius: 15px; width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-top: 5px solid #ff7e5f; }
        h2 { color: #333; text-align: center; }
        label { display: block; margin-top: 15px; font-size: 14px; color: #666; }
        input, textarea { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #333; color: white; border: none; border-radius: 8px; margin-top: 20px; cursor: pointer; font-weight: 600; }
        button:hover { background: #ff7e5f; }
        .back { display: block; text-align: center; margin-top: 15px; color: #999; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Tambah Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Minuman</label>
            <input type="text" name="nama_menu" placeholder="Contoh: Green Tea Cheese" required>
            
            <label>Harga (Rp)</label>
            <input type="number" name="harga" placeholder="8000" required>
            
            <label>Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="3" placeholder="Jelaskan rasa minumannya..."></textarea>
            
            <label>Foto Produk</label>
            <input type="file" name="gambar" required>
            
            <button type="submit" name="submit">Simpan ke Daftar Menu</button>
            <a href="menu.php" class="back">Batal & Kembali</a>
        </form>
    </div>
</body>
</html>