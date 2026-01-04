<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ambil nama file gambar dulu supaya bisa dihapus dari folder
    $data = mysqli_query($conn, "SELECT gambar FROM menu WHERE id=$id");
    $row = mysqli_fetch_assoc($data);
    unlink("img/" . $row['gambar']); // Hapus foto dari folder img/

    mysqli_query($conn, "DELETE FROM menu WHERE id=$id");
}

header("Location: menu.php"); // Balik lagi ke halaman menu
?>