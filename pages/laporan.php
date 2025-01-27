<?php
include("../includes/header.php");  // Include header
include("../includes/navbar.php");  // Include navigation menu
include("../config/db.php");        // Include database connection
?>

<h2 class="page-title">Laporan Produksi</h2>

<!-- Show success/error messages after editing or deleting data -->
<?php
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']); // Sanitize input
    $messages = [
        'delete_success' => ['text' => 'Data berhasil dihapus.', 'class' => 'success-message'],
        'edit_success' => ['text' => 'Data berhasil diperbarui.', 'class' => 'success-message'],
        'delete_error' => ['text' => 'Terjadi kesalahan saat menghapus data.', 'class' => 'error-message'],
        'edit_error' => ['text' => 'Terjadi kesalahan saat memperbarui data.', 'class' => 'error-message'],
    ];

    if (array_key_exists($message, $messages)) {
        echo "<p class='{$messages[$message]['class']}'>{$messages[$message]['text']}</p>";
    }
}
?>

<!-- Filter by Date and Product Type -->
<div class="filter-container">
    <form method="GET" action="laporan.php">
        <label for="start_date">Dari Tanggal:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">

        <label for="end_date">Hingga Tanggal:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">

        <label for="jenis_produk">Jenis Produk:</label>
        <input type="text" id="jenis_produk" name="jenis_produk" value="<?php echo htmlspecialchars($_GET['jenis_produk'] ?? ''); ?>" placeholder="Cari Jenis Produk">

        <button type="submit" class="btn"><i class="fa fa-filter"></i> Filter Laporan</button>
    </form>
</div>

<!-- Download PDF Button -->
<div class="download-print-container">
    <button id="downloadPDF" class="btn"><i class="fa fa-file-pdf-o"></i> Unduh PDF</button>
</div>

<!-- Show message if no data is available -->
<?php
$query_check = "SELECT COUNT(*) AS total FROM input_data";
$result_check = mysqli_query($conn, $query_check);
$row_check = mysqli_fetch_assoc($result_check);
if ($row_check['total'] == 0) {
    echo "<p class='no-data'>Tidak ada data produksi yang tersedia.</p>";
}
?>

<!-- Production Report Table -->
<div class="table-container">
    <table id="laporanTable" class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Produk</th>
                <th>Jumlah Produk</th>
                <th>Kode Batch</th>
                <th>Tanggal</th>
                <th>Gambar Produk</th>
                <th class="actions-column">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $limit = 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $start_from = ($page - 1) * $limit;

            $start_date = mysqli_real_escape_string($conn, $_GET['start_date'] ?? '');
            $end_date = mysqli_real_escape_string($conn, $_GET['end_date'] ?? '');
            $jenis_produk = mysqli_real_escape_string($conn, $_GET['jenis_produk'] ?? '');

            // Prepare filter query
            $query_filter = "1=1";
            if ($start_date && $end_date) {
                $query_filter .= " AND tanggal BETWEEN ? AND ?";
            }
            if ($jenis_produk) {
                $query_filter .= " AND jenis_produk LIKE ?";
            }

            // Prepare statement to avoid SQL injection
            $stmt = mysqli_prepare($conn, "SELECT * FROM input_data WHERE $query_filter ORDER BY tanggal DESC LIMIT ?, ?");
            if ($start_date && $end_date) {
                mysqli_stmt_bind_param($stmt, 'ssii', $start_date, $end_date, $start_from, $limit);
            } elseif ($jenis_produk) {
                $jenis_produk = "%$jenis_produk%";
                mysqli_stmt_bind_param($stmt, 'sii', $jenis_produk, $start_from, $limit);
            } else {
                mysqli_stmt_bind_param($stmt, 'ii', $start_from, $limit);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $no = $start_from + 1;
            while ($row = mysqli_fetch_assoc($result)) :
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['jenis_produk']); ?></td>
                <td><?php echo htmlspecialchars($row['jumlah_produk']); ?></td>
                <td><?php echo htmlspecialchars($row['kode_batch']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($row['tanggal'])); ?></td>
                <td>
                    <?php
                    $image_path = '../images/' . htmlspecialchars($row['gambar_produk']);
                    if (!empty($row['gambar_produk']) && file_exists($image_path)) : ?>
                        <img src="<?php echo $image_path; ?>" alt="Product Image" width="100">
                    <?php else : ?>
                        <img src="../images/placeholder.png" alt="No Image" width="100">
                    <?php endif; ?>
                </td>
                <td class="actions-column">
                    <a href="edit_laporan.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                    <a href="hapus_laporan.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="pagination">
    <?php
    $query_total = "SELECT COUNT(*) AS total FROM input_data WHERE $query_filter";
    $result_total = mysqli_query($conn, $query_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_pages = ceil($row_total['total'] / $limit);

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        echo "<a href='laporan.php?page=$i' class='page-link $active'>$i</a>";
    }
    ?>
</div>

<!-- Button to go back to Dashboard -->
<div class="back-to-dashboard">
    <a href="dashboard.php" class="btn"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>

<?php
include("../includes/footer.php");
?>

<!-- Include jsPDF, html2pdf, and printThis library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    // Download PDF functionality
    document.getElementById('downloadPDF').addEventListener('click', function() {
        const table = document.getElementById('laporanTable');
        const actionsColumn = document.querySelectorAll('#laporanTable .actions-column');

        // Hide action columns before generating PDF
        actionsColumn.forEach((column) => column.style.display = 'none');

        html2pdf().from(table)
            .set({
                margin: 10,
                filename: 'Laporan_Produksi.pdf',
                html2canvas: { scale: 2, letterRendering: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            })
            .toPdf().get('pdf')
            .then(function(pdf) {
                pdf.save('Laporan_Produksi.pdf');
            })
            .finally(function() {
                actionsColumn.forEach((column) => column.style.display = '');
            });
    });
</script>

<style>
    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination .page-link {
        margin: 0 5px;
        padding: 5px 10px;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        text-decoration: none;
    }

    .pagination .active {
        background-color: #007bff;
        color: white;
    }

    .no-data {
        text-align: center;
        color: red;
        font-weight: bold;
    }
</style>
