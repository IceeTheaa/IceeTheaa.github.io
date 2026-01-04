<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM menu WHERE id=$id");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    
    if ($_FILES['gambar']['name'] != "") {
        $filename = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "img/" . $filename);
        mysqli_query($conn, "UPDATE menu SET nama_menu='$nama', harga='$harga', deskripsi='$deskripsi', gambar='$filename' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE menu SET nama_menu='$nama', harga='$harga', deskripsi='$deskripsi' WHERE id=$id");
    }
    header("Location: menu.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 50px; }
        .card { background: white; padding: 30px; border-radius: 15px; width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-top: 5px solid #3498db; }
        h2 { color: #333; text-align: center; }
        input, textarea { width: 100%; padding: 12px; margin-top: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #333; color: white; border: none; border-radius: 8px; margin-top: 20px; cursor: pointer; }
        button:hover { background: #3498db; }
        .old-img { width: 100px; margin-top: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Edit Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nama_menu" value="<?= $row['nama_menu']; ?>" required>
            <input type="number" name="harga" value="<?= $row['harga']; ?>" required>
            <textarea name="deskripsi"><?= $row['deskripsi']; ?></textarea>
            <p style="font-size: 12px; color: #999; margin-top: 10px;">Foto saat ini:</p>
            <img src="img/<?= $row['gambar']; ?>" class="old-img"><br>
            <input type="file" name="gambar">
            <button type="submit" name="update">Update Menu</button>
            <a href="menu.php" style="display:block; text-align:center; margin-top:10px; color:#999; text-decoration:none;">Batal</a>
        </form>
    </div>
</body>
</html>