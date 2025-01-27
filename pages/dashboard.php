<?php
// Memulai session
session_start();

// Mengecek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, redirect ke halaman login
    exit();
}

// Meng-include file koneksi database
include("../config/db.php");

// Mengecek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mengambil data status mesin dari database
$query_mesin = "SELECT * FROM mesin";
$result_mesin = mysqli_query($conn, $query_mesin);
if (!$result_mesin) {
    die("Query gagal: " . mysqli_error($conn));
}

// Menyimpan hasil query ke array (jika ada data)
$mesin = [];
if ($result_mesin && mysqli_num_rows($result_mesin) > 0) {
    $mesin = mysqli_fetch_all($result_mesin, MYSQLI_ASSOC);
}

// Mengambil data laporan produksi untuk grafik berdasarkan jenis produk
$query_jenis_produk = "SELECT jenis_produk, SUM(jumlah_produk) as total_produk FROM input_data GROUP BY jenis_produk";
$result_jenis_produk = mysqli_query($conn, $query_jenis_produk);
if (!$result_jenis_produk) {
    die("Query gagal: " . mysqli_error($conn));
}

$jenis_produk_data = [];
while ($row = mysqli_fetch_assoc($result_jenis_produk)) {
    $jenis_produk_data[] = $row;
}

// Menyiapkan data untuk grafik
$labels = [];
$data = [];
foreach ($jenis_produk_data as $item) {
    $labels[] = $item['jenis_produk'];
    $data[] = $item['total_produk'];
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Dashboard</h2>

    <!-- Menampilkan pesan selamat datang -->
    <div class="text-center mb-4">
        <h4>Welcome, <?php echo $_SESSION['username']; ?>!</h4>
    </div>

    <!-- Tanggal dan Waktu Sekarang -->
    <div class="text-center mb-4" id="currentDateTime"></div>

    <!-- Menampilkan Status Mesin -->
    <div class="row">
        <?php if (count($mesin) === 0): ?>
            <div class="alert alert-warning text-center" role="alert">
                Tidak ada data mesin.
            </div>
        <?php else: ?>
            <!-- Menampilkan setiap mesin dalam card -->
            <?php foreach ($mesin as $mesin_item): ?>
                <div class="col-md-4 mb-3">
                    <div class="card <?php echo ($mesin_item['status'] == 'Aktif') ? 'bg-success text-white' : 'bg-danger text-white'; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $mesin_item['nama_mesin']; ?></h5>
                            <p class="card-text">
                                <strong>Status: </strong>
                                <?php
                                    if ($mesin_item['status'] == 'Aktif') {
                                        echo '<span class="badge badge-success">Aktif</span>';
                                    } else {
                                        echo '<span class="badge badge-danger">Tidak Aktif</span>';
                                    }
                                ?>
                            </p>
                            <p class="card-text">
                                <strong>Lokasi: </strong> <?php echo $mesin_item['lokasi']; ?>
                            </p>
                            <p class="card-text">
                                <strong>Waktu Terakhir Diperiksa: </strong> <?php echo date("d-m-Y H:i:s", strtotime($mesin_item['waktu_terakhir'])); ?>
                            </p>
                            <!-- Tombol Edit atau Hapus -->
                            <a href="edit_mesin.php?id=<?php echo $mesin_item['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_mesin.php?id=<?php echo $mesin_item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Grafik Laporan Produksi Berdasarkan Jenis Produk -->
    <div class="mt-5">
        <h2 class="text-center mb-4">Grafik Produksi Berdasarkan Jenis Produk</h2>
        <canvas id="jenisProdukChart"></canvas>
    </div>
</div>

<?php include("../includes/footer.php"); ?>

<!-- Tambahkan CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data untuk grafik
    const labels = <?php echo json_encode($labels); ?>;
    const data = {
        labels: labels,
        datasets: [{
            label: 'Total Produksi',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Konfigurasi grafik
    const config = {
        type: 'bar', // Jenis grafik (bar chart)
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Grafik Produksi per Jenis Produk'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Jenis Produk'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Barang'
                    }
                }
            }
        }
    };

    // Render grafik
    const jenisProdukChart = new Chart(
        document.getElementById('jenisProdukChart'),
        config
    );

    // Menampilkan tanggal dan waktu saat ini di bawah teks "Dashboard"
    function displayDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        };
        const currentDateTime = now.toLocaleString('id-ID', options);
        document.getElementById('currentDateTime').innerText = currentDateTime;
    }

    // Memanggil fungsi untuk menampilkan waktu saat halaman dimuat
    displayDateTime();

    // Memperbarui waktu setiap detik
    setInterval(displayDateTime, 1000);
</script>

<style>
    /* Menambahkan jarak antara tanggal/waktu dan bagian bawah halaman */
    #currentDateTime {
        margin-bottom: 30px; /* Menambahkan jarak 30px antara elemen dan footer */
    }
</style>
