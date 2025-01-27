<?php
// Meng-include file koneksi database
include("../config/db.php");  // Pastikan path ini sesuai

// Mengecek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mengambil data mesin berdasarkan ID
$id = $_GET['id'];
$query = "SELECT * FROM mesin WHERE id = $id";
$result = mysqli_query($conn, $query);

// Mengecek apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$mesin_item = mysqli_fetch_assoc($result);

// Mengecek jika ada form submit untuk mengupdate data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mesin = $_POST['nama_mesin'];
    $status = $_POST['status'];
    $lokasi = $_POST['lokasi'];
    $waktu_terakhir = date("Y-m-d H:i:s");

    // Update data mesin
    $update_query = "UPDATE mesin SET nama_mesin='$nama_mesin', status='$status', lokasi='$lokasi', waktu_terakhir='$waktu_terakhir' WHERE id=$id";
    if (mysqli_query($conn, $update_query)) {
        header("Location: dashboard.php"); // Redirect ke dashboard setelah berhasil update
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Edit Mesin</h2>

    <!-- Form Edit Mesin -->
    <div class="mb-3">
        <form method="POST">
            <div class="form-group">
                <label for="nama_mesin">Nama Mesin</label>
                <input type="text" class="form-control" id="nama_mesin" name="nama_mesin" required value="<?php echo $mesin_item['nama_mesin']; ?>">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Aktif" <?php echo ($mesin_item['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?php echo ($mesin_item['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" required value="<?php echo $mesin_item['lokasi']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Mesin</button>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
