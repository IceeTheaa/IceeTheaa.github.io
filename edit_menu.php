<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM menu WHERE id=$id");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $harga = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu | Indo Ice Tea Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff7e5f;
            --primary-dark: #e66e52;
            --navbar-orange: #ff9933;
            --dark: #2d3436;
            --bg: #f4f4f9;
            --gray: #888;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg); 
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* --- Header Sesuai Index & Menu --- */
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
            font-size: 1.5rem; 
            font-weight: 700; 
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        /* --- Main Container --- */
        .container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 30px; 
            width: 100%;
            max-width: 450px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.05); 
            border: 2px solid #eee;
        }

        h2 { 
            color: var(--dark); 
            text-align: center; 
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray);
            margin-bottom: 8px;
            display: block;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* --- Input Styles --- */
        input[type="text"], 
        input[type="number"], 
        textarea { 
            width: 100%; 
            padding: 14px 18px; 
            border: 1px solid #ddd; 
            border-radius: 15px; 
            box-sizing: border-box; 
            font-family: 'Poppins';
            font-size: 0.95rem;
            background: #fcfcfc;
            transition: 0.3s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 126, 95, 0.1);
            background: #fff;
        }

        textarea { height: 100px; resize: none; }

        /* --- Image Preview Section --- */
        .image-preview-wrapper {
            background: #fdfaf9;
            padding: 15px;
            border-radius: 20px;
            margin-top: 10px;
            border: 1px dashed #ddd;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .old-img { 
            width: 80px; 
            height: 80px; 
            object-fit: cover; 
            border-radius: 12px; 
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .file-input-custom {
            font-size: 0.8rem;
            color: #777;
        }

        /* --- Buttons --- */
        .btn-update { 
            width: 100%; 
            padding: 16px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 15px; 
            margin-top: 30px; 
            cursor: pointer; 
            font-weight: 700;
            font-size: 1rem;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(255, 126, 95, 0.2);
        }

        .btn-update:hover { 
            background: var(--primary-dark); 
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(255, 126, 95, 0.3);
        }

        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #bbb;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-cancel:hover { color: #e74c3c; }

    </style>
</head>
<body>

    <header>
        <a href="menu.php" class="logo">🍹 Indo Ice Tea Admin</a>
    </header>

    <div class="container">
        <div class="card">
            <h2>Edit Menu</h2>
            <form method="POST" enctype="multipart/form-data">
                
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" value="<?= htmlspecialchars($row['nama_menu']); ?>" required>
                
                <label>Harga Produk (Rp)</label>
                <input type="number" name="harga" value="<?= $row['harga']; ?>" required>
                
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi"><?= htmlspecialchars($row['deskripsi']); ?></textarea>
                
                <label>Foto Produk</label>
                <div class="image-preview-wrapper">
                    <img src="img/<?= $row['gambar']; ?>" class="old-img" alt="Current Image">
                    <div>
                        <span style="display:block; font-size: 0.7rem; color: #999; margin-bottom: 5px;">Ganti foto baru:</span>
                        <input type="file" name="gambar" class="file-input-custom">
                    </div>
                </div>

                <button type="submit" name="update" class="btn-update">Simpan Perubahan</button>
                <a href="menu.php" class="btn-cancel">Batal dan Kembali</a>
            </form>
        </div>
    </div>

</body>
</html>