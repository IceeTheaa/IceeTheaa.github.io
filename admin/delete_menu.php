<?php
session_start();
require '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../menu.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM menu WHERE id=$id");

header("Location: ../menu.php");
