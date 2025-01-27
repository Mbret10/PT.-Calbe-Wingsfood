<?php
require('../assets/fpdf/fpdf.php');
include("../config/db.php");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Header
$pdf->Cell(0, 10, 'Laporan Produksi', 0, 1, 'C');
$pdf->Ln(5);

// Kolom Header Tabel
$pdf->Cell(10, 10, 'No', 1);
$pdf->Cell(50, 10, 'Jenis Produk', 1);
$pdf->Cell(50, 10, 'Jumlah Produk', 1);
$pdf->Cell(50, 10, 'Kode Batch', 1);
$pdf->Ln();

$query = "SELECT * FROM input_data";
$result = mysqli_query($conn, $query);

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(10, 10, $no++, 1);
    $pdf->Cell(50, 10, $row['jenis_produk'], 1);
    $pdf->Cell(50, 10, $row['jumlah_produk'], 1);
    $pdf->Cell(50, 10, $row['kode_batch'], 1);
    $pdf->Ln();
}

$pdf->Output();
?>
