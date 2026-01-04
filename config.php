<?php
$host = "localhost";
$user = "root"; // Sesuaikan dengan username database kamu
$pass = "";     // Sesuaikan dengan password database kamu
$db   = "db_icetea";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>