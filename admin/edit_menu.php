<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../menu.php");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM menu WHERE id=$id")
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    mysqli_query($conn, "UPDATE menu SET
        nama='$nama',
        deskripsi='$deskripsi',
        harga='$harga'
        WHERE id=$id
    ");

    header("Location: ../menu.php");
}
?>
<form method="post">
    <h2>Edit Menu</h2>
    <input name="nama" value="<?= $data['nama'] ?>">
    <textarea name="deskripsi"><?= $data['deskripsi'] ?></textarea>
    <input name="harga" value="<?= $data['harga'] ?>">
    <button>Update</button>
</form>
