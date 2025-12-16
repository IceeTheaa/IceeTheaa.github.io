<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../menu.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];

    move_uploaded_file($_FILES['gambar']['tmp_name'], "../img/".$gambar);

    mysqli_query($conn, "INSERT INTO menu VALUES(
        null,'$nama','$deskripsi','$harga','$gambar'
    )");

    header("Location: ../menu.php");
}
?>
<form method="post" enctype="multipart/form-data">
    <h2>Tambah Menu</h2>
    <input name="nama" placeholder="Nama Menu" required>
    <textarea name="deskripsi"></textarea>
    <input name="harga" type="number" required>
    <input type="file" name="gambar" required>
    <button>Simpan</button>
</form>
