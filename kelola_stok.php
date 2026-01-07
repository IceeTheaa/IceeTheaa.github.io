<?php
session_start();
include 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Proses Update Stok Cepat
if (isset($_POST['update_stok'])) {
    $id = $_POST['id_menu'];
    $stok_baru = $_POST['stok'];
    mysqli_query($conn, "UPDATE menu SET stok = '$stok_baru' WHERE id = '$id'");
    // Refresh halaman agar data terupdate
    header("Location: kelola_stok.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Stok | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #ff7e5f; --sidebar-width: 250px; }
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        
        /* Copy style sidebar dari admin_dashboard.php */
        .sidebar { width: var(--sidebar-width); background: white; height: 100vh; position: fixed; padding: 20px; display: flex; flex-direction: column; border-right: 1px solid #eee; }
        .sidebar h2 { color: var(--primary); font-size: 1.4rem; margin-bottom: 40px; }
        .menu-link { text-decoration: none; color: #777; padding: 12px 15px; margin-bottom: 10px; border-radius: 10px; display: flex; align-items: center; gap: 15px; transition: 0.3s; }
        .menu-link:hover, .menu-link.active { background: #fff0eb; color: var(--primary); }
        .logout { margin-top: auto; color: #e74c3c; }

        .main-content { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); padding: 40px; }

        /* Table Styles */
        .table-container { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #888; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
        
        .input-stok { 
            width: 60px; padding: 8px; border-radius: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold;
        }
        .btn-update { background: var(--primary); color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 12px; }
        .btn-update:hover { background: #e66e52; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2><i class="fas fa-lemon"></i> Admin Panel</h2>
        <a href="admin_dashboard.php" class="menu-link"><i class="fas fa-home"></i> Dashboard</a>
        <a href="kelola_stok.php" class="menu-link active"><i class="fas fa-box-open"></i> Kelola Stok</a>
        <a href="riwayat_pesanan.php" class="menu-link"><i class="fas fa-history"></i> Riwayat Pesanan</a>
        <a href="menu.php" class="menu-link"><i class="fas fa-eye"></i> Lihat Web</a>
        <a href="logout.php" class="menu-link logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1>📦 Manajemen Stok Barang</h1>
        <p>Update jumlah stok secara real-time di sini.</p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Sisa Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM menu ORDER BY stok ASC");
                    while($row = mysqli_fetch_array($query)) :
                        $stok_alert = ($row['stok'] < 10) ? 'color:red; background:#ffeaea;' : '';
                    ?>
                    <tr>
                        <td><img src="img/<?php echo $row['gambar']; ?>" width="50" style="border-radius:10px;"></td>
                        <td><?php echo $row['nama_menu']; ?></td>
                        <td>Rp <?php echo number_format($row['harga']); ?></td>
                        
                        <form method="POST">
                            <input type="hidden" name="id_menu" value="<?php echo $row['id']; ?>">
                            <td>
                                <input type="number" name="stok" class="input-stok" value="<?php echo $row['stok']; ?>" style="<?php echo $stok_alert; ?>">
                            </td>
                            <td>
                                <button type="submit" name="update_stok" class="btn-update">Update</button>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>