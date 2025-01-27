<?php
$host = "localhost";
$user = "root";        // Username MySQL default
$password = "";        // Password MySQL default (kosong jika belum diubah)
$database = "wingsfood"; // Ganti dengan nama database Anda

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
