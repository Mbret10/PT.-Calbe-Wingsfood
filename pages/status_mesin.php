<?php
// Meng-include file koneksi database
include("../config/db.php");

// Mengecek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mengecek jika ada form submit untuk menambah atau mengupdate data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'] ?? null;
    $nama_mesin = $_POST['nama_mesin'];
    $status = $_POST['status'];
    $lokasi = $_POST['lokasi'];
    $waktu_terakhir = $_POST['waktu_terakhir']; // Mengambil input waktu terakhir

    // Jika ID ada, berarti update data
    if ($id) {
        $query = "UPDATE mesin SET nama_mesin='$nama_mesin', status='$status', lokasi='$lokasi', waktu_terakhir='$waktu_terakhir' WHERE id=$id";
    } else {
        // Jika ID kosong, berarti tambah data baru
        $query = "INSERT INTO mesin (nama_mesin, status, lokasi, waktu_terakhir) VALUES ('$nama_mesin', '$status', '$lokasi', '$waktu_terakhir')";
    }

    // Menjalankan query
    if (mysqli_query($conn, $query)) {
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect untuk menghindari submit ulang
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Mengambil data status mesin dari database dan mengurutkan berdasarkan ID secara ascending
$query = "SELECT * FROM mesin ORDER BY id ASC";
$result = mysqli_query($conn, $query);

// Mengecek apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

$mesin = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Status Mesin</h2>

    <!-- Form untuk Input dan Edit Data Mesin -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title"><?php echo isset($mesin_item) ? 'Update Mesin' : 'Tambah Mesin'; ?></h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo isset($mesin_item) ? $mesin_item['id'] : ''; ?>">
                <div class="form-group">
                    <label for="nama_mesin">Nama Mesin</label>
                    <input type="text" class="form-control" id="nama_mesin" name="nama_mesin" required value="<?php echo isset($mesin_item) ? $mesin_item['nama_mesin'] : ''; ?>" placeholder="Masukkan nama mesin">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Aktif" <?php echo (isset($mesin_item) && $mesin_item['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php echo (isset($mesin_item) && $mesin_item['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" required value="<?php echo isset($mesin_item) ? $mesin_item['lokasi'] : ''; ?>" placeholder="Masukkan lokasi mesin">
                </div>
                <div class="form-group">
                    <label for="waktu_terakhir">Waktu Terakhir Diperiksa</label>
                    <input type="datetime-local" class="form-control" id="waktu_terakhir" name="waktu_terakhir" required value="<?php echo isset($mesin_item) ? date('Y-m-d\TH:i', strtotime($mesin_item['waktu_terakhir'])) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary mt-3"><?php echo isset($mesin_item) ? 'Update Mesin' : 'Tambah Mesin'; ?></button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Mesin -->
    <?php if (count($mesin) === 0): ?>
        <div class="alert alert-warning text-center" role="alert">
            Tidak ada data mesin.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Mesin</h4>
                <button class="btn btn-success float-right" onclick="printTable()">Print Daftar Mesin</button> <!-- Tombol Print -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="text-align: center; width: 100%;" id="mesinTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Mesin</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Waktu Terakhir Diperiksa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($mesin as $mesin_item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $mesin_item['nama_mesin']; ?></td>
                                    <td>
                                        <?php
                                            if ($mesin_item['status'] == 'Aktif') {
                                                echo '<span class="badge badge-success">Aktif</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Tidak Aktif</span>';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo $mesin_item['lokasi']; ?></td>
                                    <td><?php echo date("m/d/Y H:i", strtotime($mesin_item['waktu_terakhir'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>

<!-- Menambahkan JavaScript untuk fitur print -->
<script>
    function printTable() {
        var table = document.getElementById("mesinTable"); // Ambil elemen tabel
        var printWindow = window.open('', '', 'height=500, width=800'); // Membuka jendela print
        printWindow.document.write('<html><head><title>Daftar Mesin</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('table, th, td { border: 1px solid black; }');
        printWindow.document.write('th, td { padding: 8px; text-align: center; }');
        printWindow.document.write('th { background-color: #f2f2f2; }');
        printWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
        
        // Tambahkan style untuk judul Daftar Mesin agar berada di tengah
        printWindow.document.write('h2 { text-align: center; font-size: 24px; margin-top: 20px; }');
        
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Daftar Mesin</h2>'); // Judul Daftar Mesin
        printWindow.document.write(table.outerHTML); // Menambahkan tabel ke dalam jendela print
        printWindow.document.write('</body></html>');
        printWindow.document.close(); // Menutup dokumen print
        printWindow.print(); // Menjalankan perintah print
    }
</script>


<style>
    /* CSS untuk memperbaiki tabel */
    .table th, .table td {
        padding: 12px; /* Memberikan padding agar sel tabel lebih lapang */
    }

    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th, .table td {
        border: 1px solid #dee2e6; /* Menambahkan border untuk setiap kolom */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9; /* Memberikan warna latar belakang untuk baris ganjil */
    }

    .badge {
        padding: 5px 10px;
    }

    /* Memberikan jarak antar kolom untuk membuat tabel lebih rapi */
    .table-responsive {
        margin-top: 20px;
    }
</style>
