<?php
session_start();
include("../config/db.php");

if (isset($_POST['submit'])) {
    // Cek apakah checkbox telah dicentang
    if (!isset($_POST['terms'])) {
        echo "<script>alert('Harap centang syarat dan ketentuan sebelum login!');</script>";
    } else {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
        } else {
            echo "<script>alert('Username atau Password salah!');</script>";
        }
    }
}
?>

<?php include("../includes/header.php"); ?>

<!-- Login Form -->
<div class="login-container">
    <h2 class="text-center">Login</h2>
    <form method="POST" action="" class="login-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required placeholder="Masukkan username Anda">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Masukkan password Anda">
        </div>

        <!-- Checkbox untuk menyetujui syarat dan ketentuan -->
        <div class="form-group checkbox-container">
            <input type="checkbox" name="terms" id="terms" required>
            <label for="terms" class="terms-label">
                Saya setuju dengan <a href="javascript:void(0);" id="termsLink" class="terms-link">syarat dan ketentuan</a>
            </label>
        </div>

        <div class="form-group text-center">
            <button type="submit" name="submit" class="btn-submit">Login</button>
        </div>
    </form>

</div>

<?php include("../includes/footer.php"); ?>

<!-- Include CSS for styling -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('../assets/images/login.png'); /* Ganti dengan path gambar yang sesuai */
        background-size: cover;
        background-position: center;
        margin: 0;
        padding: 0;
        height: 100vh;
    }

    .login-container {
        width: 100%;
        max-width: 450px;
        margin: 100px auto;
        background-color: rgba(255, 255, 255, 0.9); /* Semi transparan */
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .login-container h2 {
        font-size: 28px;
        color: #333;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #007bff;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .checkbox-container input {
        margin-right: 10px;
        width: auto;
    }

    .terms-label {
        font-size: 14px;
        color: #333;
    }

    .terms-link {
        color: #007bff;
        text-decoration: none;
    }

    .terms-link:hover {
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    .text-center {
        text-align: center;
    }

    /* Link Registrasi */
    .register-link {
        color: #007bff;
        text-decoration: none;
    }

    .register-link:hover {
        text-decoration: underline;
    }
</style>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert2 Popup for Terms and Conditions -->
<script>
    document.getElementById('termsLink').addEventListener('click', function() {
        Swal.fire({
            title: 'Syarat dan Ketentuan',
            html: ` 
                <h6>1. Penggunaan Akun</h6>
                <p>Pengguna wajib menggunakan akun dengan bijak dan tidak menyalahgunakan fasilitas yang diberikan.</p>
                <h6>2. Keamanan Akun</h6>
                <p>Pengguna bertanggung jawab untuk menjaga kerahasiaan akun dan password.</p>
                <h6>3. Pelanggaran</h6>
                <p>Setiap pelanggaran terhadap syarat dan ketentuan ini dapat berakibat pada pemblokiran akun.</p>
                <h6>4. Pengakhiran Akun</h6>
                <p>Pengguna dapat mengakhiri akun kapan saja dengan menghubungi admin.</p>
                <p>Harap baca dengan seksama sebelum melanjutkan!</p>
            `,
            icon: 'info',
            confirmButtonText: 'Saya Mengerti',
            showCloseButton: true,
            focusConfirm: false
        });
    });
</script>
