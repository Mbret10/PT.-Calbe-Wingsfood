root_project/
│
├── assets/                # Folder untuk file statis seperti CSS, JS, gambar
│   ├── css/               # Folder untuk file CSS
│   │   └── style.css      # File CSS utama
│   ├── js/                # Folder untuk file JavaScript
│   │   └── script.js      # File JS utama
│   └── images/            # Folder untuk gambar
│       └── logo.png       # Contoh file gambar/logo
│
├── config/                # Konfigurasi database
│   └── db.php             # File koneksi ke database MySQL
│
├── includes/              # File bagian yang digunakan berulang (header, footer, dll.)
│   ├── header.php         # Header website
│   ├── footer.php         # Footer website
│   └── navbar.php         # Navigasi menu
│
├── pages/                 # Folder untuk halaman utama website
│   ├── login.php          # Halaman Login
│   ├── dashboard.php      # Dashboard Utama
│   ├── input_data.php     # Halaman Input Data
│   ├── laporan.php        # Halaman Laporan
│   └── notifikasi.php     # Halaman Notifikasi
│
├── reports/               # Folder untuk file laporan (jika hasil laporan berupa file yang bisa diunduh)
│   └── sample_report.pdf  # Contoh file laporan
│
├── index.php              # Halaman utama/beranda (redirect ke login.php atau dashboard.php)
├── logout.php             # Proses logout user
└── README.md              # Dokumentasi singkat proyek
