<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    
    // Decode data stok
    $data_stok = json_decode($_POST['data_stok'], true);

    if (is_array($data_stok)) {
        foreach ($data_stok as $item) {
            $nama = mysqli_real_escape_string($conn, $item['nama']);
            $qty_beli = (int) $item['jumlah'];

            // PERBAIKAN: HANYA KURANGI STOK (Booking barang)
            // Kolom 'terjual' jangan ditambah dulu karena status masih 'Baru'
            $query_update = "UPDATE menu SET stok = stok - $qty_beli WHERE nama_menu = '$nama'";
            mysqli_query($conn, $query_update);
        }
    }

    // Simpan pesanan dengan status 'Baru'
    $query_insert = "INSERT INTO pesanan (detail_pesanan, total_harga, metode_pembayaran, status_pesanan) 
                     VALUES ('$detail', '$total', '$metode', 'Baru')";
    
    if(mysqli_query($conn, $query_insert)) {
        echo "Berhasil";
    } else {
        echo "Gagal";
    }
}
?>