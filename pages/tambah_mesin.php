<?php
include("../config/db.php");

// Mengecek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses untuk menambahkan data mesin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_mesin = $_POST['nama_mesin'];
    $status = $_POST['status'];
    $lokasi = $_POST['lokasi'];
    $waktu_terakhir = date("Y-m-d H:i:s");  // Waktu saat data disimpan

    // Query untuk menambahkan data mesin
    $query = "INSERT INTO mesin (nama_mesin, status, lokasi, waktu_terakhir) 
              VALUES ('$nama_mesin', '$status', '$lokasi', '$waktu_terakhir')";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, alihkan ke halaman status_mesin.php
        header("Location: status_mesin.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Tambah Data Mesin</h2>

    <form method="POST">
        <div class="form-group">
            <label for="nama_mesin">Nama Mesin</label>
            <input type="text" class="form-control" id="nama_mesin" name="nama_mesin" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Aktif">Aktif</option>
                <option value="Non-Aktif">Non-Aktif</option>
            </select>
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
        </div>

        <button type="submit" class="btn btn-success">Tambah Mesin</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
