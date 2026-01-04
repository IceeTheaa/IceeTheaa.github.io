<?php
session_start();
include 'config.php';

// Proteksi: Hanya admin yang boleh masuk
if(!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit; 
}

// Logika Update Status
if(isset($_POST['update_status'])) {
    $id = $_POST['order_id'];
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id = '$id'");
    header("Location: riwayat_pesanan.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Admin</title>
    <link rel="stylesheet" href="css/stylemenu.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff7e5f;
            --dark: #2d3436;
            --bg: #fffaf9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 0;
            color: var(--dark);
        }

        .container {
            padding: 40px 5%;
            max-width: 1200px;
            margin: auto;
        }

        .header-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        h2 { margin: 0; color: var(--dark); font-weight: 700; }

        .btn-back {
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }

        .btn-back:hover { transform: translateX(-5px); }

        /* Card Wrapper for Table */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(255, 126, 95, 0.1);
            overflow-x: auto; /* Aman untuk tampilan mobile */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: #fff5f2;
            color: var(--primary);
            padding: 15px;
            text-align: left;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #fef0ed;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #fef0ed;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .status-baru { background: #fff3cd; color: #856404; } /* Kuning Pucat */
        .status-selesai { background: #e8f5e9; color: #2e7d32; } /* Hijau Pucat */
        .status-batal { background: #ffebee; color: #c62828; } /* Merah Pucat */

        /* Form Controls */
        select {
            padding: 8px;
            border-radius: 10px;
            border: 1px solid #eee;
            font-family: 'Poppins';
            font-size: 0.85rem;
            outline: none;
            cursor: pointer;
        }

        .btn-update {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-update:hover {
            background: #ff6b4a;
            box-shadow: 0 4px 10px rgba(255, 126, 95, 0.3);
        }

        .order-detail {
            max-width: 300px;
            line-height: 1.4;
            color: #636e72;
        }

        .price-text {
            font-weight: 700;
            color: var(--dark);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-area">
            <h2>📦 Pesanan Masuk</h2>
            <a href="menu.php" class="btn-back">← Kembali ke Menu</a>
        </div>
        
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Detail Pesanan</th>
                        <th>Total Tagihan</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY waktu_pesan DESC");
                    while($row = mysqli_fetch_assoc($res)) :
                        // Normalisasi class status agar sesuai dengan CSS
                        $status_label = $row['status_pesanan'];
                        $class_status = strtolower($status_label);
                    ?>
                    <tr>
                        <td style="color: #999; font-size: 0.8rem;">
                            <strong><?php echo date('d M', strtotime($row['waktu_pesan'])); ?></strong><br>
                            <?php echo date('H:i', strtotime($row['waktu_pesan'])); ?>
                        </td>
                        <td>
                            <div class="order-detail">
                                <?php echo nl2br($row['detail_pesanan']); ?>
                            </div>
                        </td>
                        <td>
                            <span class="price-text">Rp<?php echo number_format($row['total_harga'], 0, ',', '.'); ?></span>
                        </td>
                        <td>
                            <span style="font-weight: 600; font-size: 0.8rem;"><?php echo strtoupper($row['metode_pembayaran']); ?></span>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo $class_status; ?>">
                                <?php echo $status_label; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display: flex; gap: 8px; align-items: center;">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status_baru">
                                    <option value="Baru" <?php if($status_label == 'Baru') echo 'selected'; ?>>Baru</option>
                                    <option value="Selesai" <?php if($status_label == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                    <option value="Batal" <?php if($status_label == 'Batal') echo 'selected'; ?>>Batal</option>
                                </select>
                                <button type="submit" name="update_status" class="btn-update">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>