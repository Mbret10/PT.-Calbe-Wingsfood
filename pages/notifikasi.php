<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../config/db.php");

// Ambil data produk dari database
$query = "SELECT * FROM input_data";
$result = mysqli_query($conn, $query);

// Array untuk menyimpan produk dengan stok menipis
$low_stock_notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['jumlah_produk'] < 1000) {
        $low_stock_notifications[] = $row['jenis_produk'];
    }
}

// Reset hasil query agar bisa digunakan kembali
mysqli_data_seek($result, 0);
?>

<h2 class="page-title">Stock Produksi</h2>

<!-- Tabel Stok Produksi -->
<?php if (mysqli_num_rows($result) > 0) : ?>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Produk</th>
                    <th>Jumlah Produk</th>
                    <th>Kode Batch</th>
                    <th>Tanggal</th>
                    <th>Notifikasi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) :
                    // Tentukan apakah stok rendah atau aman
                    $stock_message = '';
                    $notification_class = '';
                    
                    if ($row['jumlah_produk'] < 1000) {
                        $stock_message = 'Stok Menipis!';
                        $notification_class = 'notification-warning';
                    } else {
                        $stock_message = 'Stok Aman!';
                        $notification_class = 'notification-success';
                    }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['jenis_produk']; ?></td>
                    <td><?php echo $row['jumlah_produk']; ?></td>
                    <td><?php echo $row['kode_batch']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td>
                        <div class="<?php echo $notification_class; ?>">
                            <strong><?php echo $stock_message; ?></strong>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p class="no-data">Tidak ada data produksi saat ini.</p>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Notifikasi stok menipis
    <?php if (!empty($low_stock_notifications)) : ?>
        const lowStockProducts = <?php echo json_encode($low_stock_notifications); ?>;
        const lowStockCount = lowStockProducts.length; // Hitung jumlah produk dengan stok menipis

        Swal.fire({
            icon: 'warning',
            title: 'Stok Menipis!',
            html: `
                <p><strong>Jumlah Produk dengan Stok Menipis: ${lowStockCount}</strong></p>
                <p><strong>Daftar Produk:</strong></p>
                <ul>${lowStockProducts.map(product => `<li>${product}</li>`).join('')}</ul>
            `,
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>
