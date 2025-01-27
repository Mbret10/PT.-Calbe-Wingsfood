<?php
include("../includes/header.php");  // Memanggil header
include("../includes/navbar.php");  // Memanggil navigasi menu
include("../config/db.php");        // Memanggil koneksi ke database

// Menangani pengeditan data jika ID diberikan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data berdasarkan ID
    $query = "SELECT * FROM input_data WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "Data tidak ditemukan.";
        exit;
    }
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah_produk = $_POST['jumlah_produk'];
    $jenis_produk = $_POST['jenis_produk'];
    $kode_batch = $_POST['kode_batch'];

    // Query untuk memperbarui data
    $query_update = "UPDATE input_data SET jumlah_produk = ?, jenis_produk = ?, kode_batch = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt_update, "issi", $jumlah_produk, $jenis_produk, $kode_batch, $id);

    if (mysqli_stmt_execute($stmt_update)) {
        // Jika update berhasil, arahkan ke laporan.php dengan notifikasi sukses
        header("Location: laporan.php?message=edit_success");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal memperbarui data.";
    }
}
?>

<h2 class="page-title">Edit Laporan Produksi</h2>

<form method="POST" action="edit_laporan.php?id=<?php echo $row['id']; ?>">
    <label for="jenis_produk">Jenis Produk:</label>
    <input type="text" id="jenis_produk" name="jenis_produk" value="<?php echo $row['jenis_produk']; ?>" required>

    <label for="jumlah_produk">Jumlah Produk:</label>
    <input type="number" id="jumlah_produk" name="jumlah_produk" value="<?php echo $row['jumlah_produk']; ?>" required>

    <label for="kode_batch">Kode Batch:</label>
    <input type="text" id="kode_batch" name="kode_batch" value="<?php echo $row['kode_batch']; ?>" required>

    <button type="submit" class="btn"><i class="fa fa-save"></i> Simpan Perubahan</button>
</form>

<div class="back-to-dashboard">
    <a href="laporan.php" class="btn"><i class="fa fa-arrow-left"></i> Kembali ke Laporan</a>
</div>

<?php
include("../includes/footer.php");  // Memanggil footer
?>
