<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil Data Dasar
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    
    // 2. Ambil Data JSON Stok & Decode jadi Array PHP
    $data_stok = json_decode($_POST['data_stok'], true);

    // 3. LOGIKA PENGURANGAN STOK (Looping)
    if (is_array($data_stok)) {
        foreach ($data_stok as $item) {
            $nama = mysqli_real_escape_string($conn, $item['nama']);
            $qty_beli = (int) $item['jumlah'];

            // Perintah SQL: Kurangi stok saat ini dengan jumlah beli
            // WHERE nama_menu cocok dengan yang dibeli
            $query_update = "UPDATE menu SET stok = stok - $qty_beli WHERE nama_menu = '$nama'";
            mysqli_query($conn, $query_update);
        }
    }

    // 4. Simpan Riwayat Pesanan (Seperti Biasa)
    $query_insert = "INSERT INTO pesanan (detail_pesanan, total_harga, metode_pembayaran, status_pesanan) 
                     VALUES ('$detail', '$total', '$metode', 'Baru')";
    
    if(mysqli_query($conn, $query_insert)) {
        echo "Berhasil";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>