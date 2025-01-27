<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika pengguna belum login, redirect ke halaman login
    exit();
}

include("../includes/header.php");  // Memanggil header
include("../includes/navbar.php");  // Memanggil navigasi menu
include("../config/db.php");        // Memanggil koneksi ke database
?>

<div class="container">
    <h2 class="page-title text-center">Input Data Produksi</h2>

    <!-- Form Input Data Produksi -->
    <form action="input_data.php" method="POST" class="form-container" enctype="multipart/form-data">
        <div class="form-group">
            <label for="jenis_produk">Jenis Produk:</label>
            <input type="text" id="jenis_produk" name="jenis_produk" class="form-input" required placeholder="Masukkan jenis produk">
        </div>

        <div class="form-group">
            <label for="jumlah_produk">Jumlah Produk:</label>
            <input type="number" id="jumlah_produk" name="jumlah_produk" class="form-input" required placeholder="Masukkan jumlah produk">
        </div>

        <!-- Kode Batch dibuat otomatis, jadi input tidak ditampilkan -->
        <input type="hidden" id="kode_batch" name="kode_batch" value="" readonly>

        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="gambar_produk">Gambar Produk:</label>
            <input type="file" id="gambar_produk" name="gambar_produk" class="form-input" accept="image/*">
        </div>

        <button type="submit" name="submit" class="btn-submit">Tambah Data</button>
    </form>

    <?php
    // Menambahkan data ke database setelah tombol submit diklik
    if (isset($_POST['submit'])) {
        // Mengambil input dari form
        $jenis_produk = mysqli_real_escape_string($conn, $_POST['jenis_produk']);
        $jumlah_produk = mysqli_real_escape_string($conn, $_POST['jumlah_produk']);
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);

        // Generate kode batch otomatis berdasarkan jenis produk dan waktu
        $kode_batch = strtoupper(substr($jenis_produk, 0, 3)) . date("YmdHis");

        // Proses upload gambar produk
        $gambar_produk = 'placeholder.png'; // Default gambar jika tidak ada yang diunggah
        if (!empty($_FILES['gambar_produk']['name'])) {
            $target_dir = "../images/";
            $gambar_produk = time() . "_" . basename($_FILES["gambar_produk"]["name"]);
            $target_file = $target_dir . $gambar_produk;

            // Validasi ukuran file gambar (maksimal 2MB)
            if ($_FILES["gambar_produk"]["size"] > 2000000) {
                echo "<p class='error-message'>Ukuran file terlalu besar (maksimum 2MB).</p>";
            } elseif (!move_uploaded_file($_FILES["gambar_produk"]["tmp_name"], $target_file)) {
                echo "<p class='error-message'>Gagal mengunggah gambar.</p>";
            }
        }

        // Query untuk menambahkan data ke tabel input_data
        $query = "INSERT INTO input_data (jenis_produk, jumlah_produk, kode_batch, tanggal, gambar_produk) 
                  VALUES ('$jenis_produk', '$jumlah_produk', '$kode_batch', '$tanggal', '$gambar_produk')";

        // Eksekusi query dan beri pesan jika sukses atau gagal
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Data produk berhasil ditambahkan!";
            header("Location: input_data.php");
            exit();
        } else {
            echo "<p class='error-message'>Error: " . mysqli_error($conn) . "</p>";
        }
    }
    ?>

    <!-- Menampilkan pesan sukses jika data berhasil ditambahkan -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="notification-container">
            <div class="notification-success">
                <strong>Success!</strong> <?php echo $_SESSION['message']; ?>
            </div>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?> 
