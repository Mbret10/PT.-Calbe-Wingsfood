<?php 
// Mulai sesi
session_start();
  
// Cek apakah user sudah melakukan logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jika tombol logout ditekan dan dikonfirmasi
    session_unset();  // Menghapus semua variabel sesi
    session_destroy(); // Menghancurkan sesi
    // Arahkan ke index.php setelah logout
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <!-- Tombol logout yang memicu konfirmasi -->
    <button id="logoutBtn">Logout</button>

    <script>
        // Menambahkan event listener untuk tombol logout
        document.getElementById("logoutBtn").addEventListener("click", function () {
            // Dialog konfirmasi
            const confirmLogout = confirm("Apakah Anda yakin ingin keluar?");
            if (confirmLogout) {
                // Kirim permintaan POST untuk logout
                const form = document.createElement('form');
                form.method = 'POST';
                document.body.appendChild(form);  // Menambahkan form ke body
                form.submit();  // Kirim form untuk logout
            } else {
                // Jika logout dibatalkan
                alert("Logout dibatalkan.");
            }
        });
    </script>
</body>
</html>
