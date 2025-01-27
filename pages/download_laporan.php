<?php
include("../config/db.php");

// Mengambil data laporan
$query = "SELECT * FROM input_data";
$result = mysqli_query($conn, $query);

// Mengatur header untuk file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="laporan_produksi.csv"');

// Membuka file output untuk ditulis
$output = fopen('php://output', 'w');

// Menulis header kolom
fputcsv($output, ['No', 'Jenis Produk', 'Jumlah Produk', 'Kode Batch', 'Tanggal']);

// Menulis data
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [$no++, $row['jenis_produk'], $row['jumlah_produk'], $row['kode_batch'], $row['tanggal']]);
}

// Menutup file output
fclose($output);
?>
