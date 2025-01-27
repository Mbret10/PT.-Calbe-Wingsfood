<?php
include("../config/db.php");

// Ambil ID dari parameter URL
$id = $_GET['id'];

// Menghapus data berdasarkan ID
$query = "DELETE FROM input_data WHERE id = $id";
if (mysqli_query($conn, $query)) {
    // Redirect ke laporan.php dengan pesan sukses
    header("Location: laporan.php?message=delete_success");
    exit();
} else {
    // Jika gagal, redirect dengan pesan error
    header("Location: laporan.php?message=delete_error");
    exit();
}
?>
