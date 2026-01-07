<?php
session_start();
include 'config.php';

// Cek Login Admin
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 1. DATA UNTUK KARTU ATAS
$total_menu = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM menu"));
$total_order = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pesanan"));

// Hitung total pendapatan (hanya yang status Selesai)
$query_income = mysqli_query($conn, "SELECT SUM(total_harga) as omset FROM pesanan WHERE status_pesanan = 'Selesai'");
$data_income = mysqli_fetch_assoc($query_income);
$omset = $data_income['omset'] ? $data_income['omset'] : 0;

// Cari menu dengan stok menipis (< 10)
$stok_nipis = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM menu WHERE stok < 10"));

// 2. DATA UNTUK GRAFIK (Pendapatan per Metode Pembayaran)
$q_metode = mysqli_query($conn, "SELECT metode_pembayaran, COUNT(*) as jumlah FROM pesanan GROUP BY metode_pembayaran");
$label_chart = [];
$data_chart = [];
while ($row = mysqli_fetch_assoc($q_metode)) {
    $label_chart[] = strtoupper($row['metode_pembayaran']);
    $data_chart[] = $row['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #ff7e5f;
            --secondary: #feb47b;
            --dark: #2d3436;
            --bg: #f4f7f6;
            --sidebar-width: 250px;
        }

        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; display: flex; }

        /* SIDEBAR STYLE */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 { color: var(--primary); font-size: 1.4rem; margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .menu-link {
            text-decoration: none; color: #777; padding: 12px 15px;
            margin-bottom: 10px; border-radius: 10px; display: flex; align-items: center; gap: 15px; font-weight: 500;
            transition: 0.3s;
        }
        .menu-link:hover, .menu-link.active { background: #fff0eb; color: var(--primary); }
        .logout { margin-top: auto; color: #e74c3c; }
        .logout:hover { background: #ffeaea; color: #c0392b; }

        /* MAIN CONTENT */
        .main-content { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); padding: 30px; }
        
        /* CARDS */
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px; }
        .card-icon { width: 50px; height: 50px; background: #fff0eb; color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .card-info h3 { margin: 0; font-size: 1.5rem; color: var(--dark); }
        .card-info p { margin: 0; font-size: 0.85rem; color: #888; }

        /* CHART SECTION */
        .chart-container { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); max-width: 600px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2><i class="fas fa-lemon"></i> Admin Panel</h2>
        <a href="admin_dashboard.php" class="menu-link active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="kelola_stok.php" class="menu-link"><i class="fas fa-box-open"></i> Kelola Stok</a>
        <a href="riwayat_pesanan.php" class="menu-link"><i class="fas fa-history"></i> Riwayat Pesanan</a>
        <a href="menu.php" class="menu-link"><i class="fas fa-eye"></i> Lihat Web</a>
        <a href="logout.php" class="menu-link logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1 style="margin-bottom: 30px; font-size: 1.8rem;">👋 Halo, Admin!</h1>

        <div class="cards-grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="card-info">
                    <p>Total Omset (Selesai)</p>
                    <h3>Rp <?php echo number_format($omset, 0, ',', '.'); ?></h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="card-info">
                    <p>Total Pesanan</p>
                    <h3><?php echo $total_order; ?> Order</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon" style="background: #ffeaea; color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="card-info">
                    <p>Stok Menipis</p>
                    <h3 style="color: #e74c3c;"><?php echo $stok_nipis; ?> Item</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-coffee"></i></div>
                <div class="card-info">
                    <p>Total Menu</p>
                    <h3><?php echo $total_menu; ?> Varian</h3>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            <div class="chart-container" style="flex: 1;">
                <h3 style="margin-top:0;">📊 Statistik Metode Pembayaran</h3>
                <canvas id="paymentChart"></canvas>
            </div>

            <div class="chart-container" style="flex: 1;">
                <h3 style="margin-top:0;">⚠️ Peringatan Stok Rendah</h3>
                <table style="width:100%; text-align:left; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #eee;">
                        <th style="padding:10px;">Nama Menu</th>
                        <th style="padding:10px;">Sisa Stok</th>
                    </tr>
                    <?php
                    $q_low = mysqli_query($conn, "SELECT nama_menu, stok FROM menu WHERE stok < 10 LIMIT 5");
                    if(mysqli_num_rows($q_low) > 0){
                        while($r = mysqli_fetch_assoc($q_low)){
                            echo "<tr><td style='padding:10px;'>{$r['nama_menu']}</td><td style='padding:10px; font-weight:bold; color:red;'>{$r['stok']}</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' style='padding:10px; text-align:center; color:green;'>Aman! Tidak ada stok kritis.</td></tr>";
                    }
                    ?>
                </table>
                <div style="margin-top: 15px; text-align:right;">
                    <a href="kelola_stok.php" style="color: var(--primary); text-decoration:none; font-weight:bold;">Kelola Stok &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi Chart.js
        const ctx = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($label_chart); ?>,
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: <?php echo json_encode($data_chart); ?>,
                    backgroundColor: ['#ff7e5f', '#feb47b', '#333'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
</body>
</html>