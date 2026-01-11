<?php
session_start();
include 'config.php';

if(!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit; 
}

if(isset($_POST['update_status'])) {
    $id = $_POST['order_id'];
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id = '$id'");
    header("Location: riwayat_pesanan.php");
}

// Ambil statistik sederhana
$total_pesanan = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pesanan"));
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE status_pesanan = 'Selesai'"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Pesanan | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff7e5f;
            --secondary: #feb47b;
            --dark: #2d3436;
            --light: #f8f9fa;
            --success: #27ae60;
            --danger: #eb4d4b;
            --warning: #f1c40f;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            color: var(--dark);
        }

        /* Navbar Style */
        .nav-header {
            background: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            padding: 30px 5%;
            max-width: 1300px;
            margin: auto;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary);
        }

        .stat-card hsmall { font-size: 12px; color: #888; text-transform: uppercase; }
        .stat-card h3 { margin: 5px 0 0 0; font-size: 20px; }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #fafafa;
            color: #888;
            padding: 15px 25px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        td {
            padding: 20px 25px;
            border-bottom: 1px solid #f8f8f8;
            font-size: 14px;
        }

        tr:hover { background-color: #fffaf9; }

        /* Status Badges Custom */
        .badge {
            padding: 6px 15px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-baru { background: #fff4e5; color: #ff9800; }
        .status-selesai { background: #e7f7ed; color: #2ecc71; }
        .status-batal { background: #ffeaea; color: #e74c3c; }

        /* Action UI */
        select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-family: inherit;
            background: #f9f9f9;
        }

        .btn-update {
            background: var(--dark);
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-update:hover { background: var(--primary); }

        .btn-back {
            text-decoration: none;
            color: #666;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover { color: var(--primary); }

        .order-items {
            font-size: 13px;
            color: #555;
            background: #fdfdfd;
            padding: 10px;
            border-radius: 8px;
            border-left: 3px solid #eee;
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .nav-header h2 { font-size: 18px; }
        }
    </style>
</head>
<body>

    <nav class="nav-header">
        <h2 style="margin:0;">🍹 Indo Ice Tea <span style="font-weight:300; font-size:16px;">| Admin Panel</span></h2>
        <a href="admin_dashboard.php" class="btn-back">← Dashboard Menu</a>
    </nav>

    <div class="container">
        
        <div class="stats-grid">
            <div class="stat-card">
                <hsmall>Total Pesanan</hsmall>
                <h3><?php echo $total_pesanan; ?> Order</h3>
            </div>
            <div class="stat-card" style="border-left-color: var(--success);">
                <hsmall>Pendapatan Selesai</hsmall>
                <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h3 style="margin:0; font-size: 18px;">📦 Daftar Pesanan Masuk</h3>
            </div>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu & ID</th>
                            <th>Detail Item</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY waktu_pesan DESC");
                        while($row = mysqli_fetch_assoc($res)) :
                            $status = $row['status_pesanan'];
                            $class_status = strtolower($status);
                        ?>
                        <tr>
                            <td>
                                <div style="font-weight:600; color:var(--primary)">#ORD-<?php echo $row['id']; ?></div>
                                <div style="font-size:11px; color:#aaa; margin-top:4px;">
                                    <?php echo date('d M Y, H:i', strtotime($row['waktu_pesan'])); ?>
                                </div>
                            </td>
                            <td>
                                <div class="order-items">
                                    <?php echo nl2br($row['detail_pesanan']); ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:700;">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></span>
                            </td>
                            <td>
                                <span style="font-size:11px; padding:3px 8px; background:#eee; border-radius:4px; font-weight:600;">
                                    <?php echo strtoupper($row['metode_pembayaran']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge status-<?php echo $class_status; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                    <select name="status_baru">
                                        <option value="Baru" <?php if($status == 'Baru') echo 'selected'; ?>>Baru</option>
                                        <option value="Selesai" <?php if($status == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                        <option value="Batal" <?php if($status == 'Batal') echo 'selected'; ?>>Batal</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update">✓</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>