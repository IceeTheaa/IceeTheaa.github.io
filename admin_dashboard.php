<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// --- 1. DATA KARTU ATAS ---
$total_menu = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM menu"));
$total_order = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pesanan"));
$stok_nipis = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM menu WHERE stok < 10"));

// Total Pendapatan (Hanya Status Selesai)
$q_income = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as omset FROM pesanan WHERE status_pesanan = 'Selesai'"));
$omset = $q_income['omset'] ?: 0;


// --- 2. DATA GRAFIK METODE PEMBAYARAN (Doughnut) ---
$q_metode = mysqli_query($conn, "SELECT metode_pembayaran, COUNT(*) as jumlah FROM pesanan GROUP BY metode_pembayaran");
$lbl_metode = []; $dat_metode = [];
while ($row = mysqli_fetch_assoc($q_metode)) {
    $lbl_metode[] = strtoupper($row['metode_pembayaran']);
    $dat_metode[] = $row['jumlah'];
}


// --- 3. DATA GRAFIK OMSET BULANAN (Line Chart) ---
// Kita siapkan array kosong untuk bulan 1 s/d 12
$pendapatan_bulanan = array_fill(0, 12, 0); 
$tahun_ini = date('Y');

$q_bulan = mysqli_query($conn, "SELECT MONTH(waktu_pesan) as bulan, SUM(total_harga) as total 
                                FROM pesanan 
                                WHERE YEAR(waktu_pesan) = '$tahun_ini' AND status_pesanan = 'Selesai' 
                                GROUP BY MONTH(waktu_pesan)");

while ($row = mysqli_fetch_assoc($q_bulan)) {
    // Bulan SQL mulainya dari 1, Array PHP mulai dari 0. Jadi dikurang 1.
    $pendapatan_bulanan[$row['bulan'] - 1] = $row['total'];
}


// --- 4. DATA GRAFIK TREN MENU TERLARIS (Bar Chart) ---
$q_trend = mysqli_query($conn, "SELECT nama_menu, terjual FROM menu ORDER BY terjual DESC LIMIT 5");
$lbl_trend = []; $dat_trend = [];
while ($row = mysqli_fetch_assoc($q_trend)) {
    $lbl_trend[] = $row['nama_menu'];
    $dat_trend[] = $row['terjual'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard & Analitik | Indo Ice Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #ff7e5f; --secondary: #feb47b; --bg: #f4f7f6; --sidebar-width: 250px; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; display: flex; }

        /* Sidebar & Layout */
        .sidebar { width: var(--sidebar-width); background: white; height: 100vh; position: fixed; padding: 20px; border-right: 1px solid #eee; display: flex; flex-direction: column; }
        .sidebar h2 { color: var(--primary); font-size: 1.4rem; margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .menu-link { text-decoration: none; color: #777; padding: 12px 15px; margin-bottom: 10px; border-radius: 10px; display: flex; align-items: center; gap: 15px; transition: 0.3s; font-weight: 500; }
        .menu-link:hover, .menu-link.active { background: #fff0eb; color: var(--primary); }
        .logout { margin-top: auto; color: #e74c3c; }
        
        .main-content { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); padding: 30px; }

        /* Kartu Statistik */
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px; }
        .card-icon { width: 50px; height: 50px; background: #fff0eb; color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .card-info h3 { margin: 0; font-size: 1.5rem; color: #333; }
        .card-info p { margin: 0; font-size: 0.85rem; color: #888; }

        /* Grid Layout Dashboard */
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 25px; }
        
        .chart-box { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
        .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .chart-header h3 { margin: 0; font-size: 1.1rem; color: #333; }

        /* Table Mini */
        .recent-orders { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .recent-orders th { text-align: left; font-size: 0.8rem; color: #aaa; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .recent-orders td { padding: 12px 0; font-size: 0.9rem; border-bottom: 1px solid #f9f9f9; }
        
        /* Responsive */
        @media (max-width: 1000px) { .dashboard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2><i class="fas fa-lemon"></i> Admin Panel</h2>
        <a href="admin_dashboard.php" class="menu-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="kelola_stok.php" class="menu-link"><i class="fas fa-box-open"></i> Kelola Stok</a>
        <a href="riwayat_pesanan.php" class="menu-link"><i class="fas fa-history"></i> Riwayat Pesanan</a>
        <a href="menu.php" class="menu-link"><i class="fas fa-eye"></i> Lihat Web</a>
        <a href="logout.php" class="menu-link logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1 style="font-size: 1.6rem; margin-bottom: 10px;">📊 Dashboard Analitik</h1>
        <p style="color:#888; margin-bottom: 30px;">Ringkasan performa penjualan Indo Ice Tea tahun <?php echo date('Y'); ?>.</p>

        <div class="cards-grid">
            <div class="card">
                <div class="card-icon"><i class="fas fa-wallet"></i></div>
                <div class="card-info"><p>Total Omset</p><h3>Rp <?php echo number_format($omset, 0, ',', '.'); ?></h3></div>
            </div>
            <div class="card">
                <div class="card-icon" style="background:#eaf6fc; color:#3498db;"><i class="fas fa-receipt"></i></div>
                <div class="card-info"><p>Total Pesanan</p><h3><?php echo $total_order; ?></h3></div>
            </div>
            <div class="card">
                <div class="card-icon" style="background:#fff5f5; color:#e74c3c;"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="card-info"><p>Stok Menipis</p><h3 style="color:#e74c3c;"><?php echo $stok_nipis; ?> Item</h3></div>
            </div>
            <div class="card">
                <div class="card-icon" style="background:#eafaf1; color:#27ae60;"><i class="fas fa-mug-hot"></i></div>
                <div class="card-info"><p>Varian Menu</p><h3><?php echo $total_menu; ?></h3></div>
            </div>
        </div>

        <div class="dashboard-grid">
            
            <div class="chart-box">
                <div class="chart-header">
                    <h3>📈 Tren Pendapatan Bulanan</h3>
                </div>
                <canvas id="incomeChart" height="120"></canvas>
            </div>

            <div class="chart-box">
                <div class="chart-header">
                    <h3>🏆 Top 5 Menu Terlaris</h3>
                </div>
                <canvas id="trendChart" height="200"></canvas>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="chart-box">
                <div class="chart-header">
                    <h3>💳 Metode Pembayaran</h3>
                </div>
                <div style="max-height: 200px; display:flex; justify-content:center;">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>

            <div class="chart-box">
                <div class="chart-header">
                    <h3>⏱️ Transaksi Terakhir</h3>
                    <a href="riwayat_pesanan.php" style="font-size:0.8rem; color:var(--primary); text-decoration:none;">Lihat Semua &rarr;</a>
                </div>
                <table class="recent-orders">
                    <tr><th>ID Pesanan</th><th>Total</th><th>Status</th></tr>
                    <?php
                    $last_orders = mysqli_query($conn, "SELECT id, total_harga, status_pesanan FROM pesanan ORDER BY waktu_pesan DESC LIMIT 5");
                    while($lo = mysqli_fetch_assoc($last_orders)):
                        $badge_color = ($lo['status_pesanan']=='Baru') ? 'orange' : (($lo['status_pesanan']=='Selesai') ? 'green' : 'red');
                    ?>
                    <tr>
                        <td>#<?php echo $lo['id']; ?></td>
                        <td>Rp <?php echo number_format($lo['total_harga']); ?></td>
                        <td><span style="color:<?php echo $badge_color; ?>; font-weight:bold; font-size:0.8rem;"><?php echo $lo['status_pesanan']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>

    <script>
        // 1. CHART PENDAPATAN (LINE)
        new Chart(document.getElementById('incomeChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?php echo json_encode($pendapatan_bulanan); ?>,
                    borderColor: '#ff7e5f',
                    backgroundColor: 'rgba(255, 126, 95, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Membuat garis melengkung halus
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. CHART MENU TERLARIS (BAR)
        new Chart(document.getElementById('trendChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($lbl_trend); ?>,
                datasets: [{
                    label: 'Terjual (Pcs)',
                    data: <?php echo json_encode($dat_trend); ?>,
                    backgroundColor: ['#ff7e5f', '#feb47b', '#3498db', '#2ecc71', '#9b59b6'],
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y', // Membuat bar chart horizontal agar nama menu terbaca jelas
                plugins: { legend: { display: false } }
            }
        });

        // 3. CHART METODE PEMBAYARAN (Doughnut)
        new Chart(document.getElementById('paymentChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($lbl_metode); ?>,
                datasets: [{
                    data: <?php echo json_encode($dat_metode); ?>,
                    backgroundColor: ['#3498db', '#e74c3c'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
    </script>
</body>
</html>