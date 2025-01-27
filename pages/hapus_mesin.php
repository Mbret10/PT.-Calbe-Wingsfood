<?php
include("../config/db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data mesin berdasarkan ID
    $query = "DELETE FROM mesin WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: status_mesin.php");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "ID Mesin tidak ditemukan.";
}
?>
