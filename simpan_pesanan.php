<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);

    $query = "INSERT INTO pesanan (detail_pesanan, total_harga, metode_pembayaran, status_pesanan) 
              VALUES ('$detail', '$total', '$metode', 'Baru')";
    
    mysqli_query($conn, $query);
    echo "Berhasil";
}
?>