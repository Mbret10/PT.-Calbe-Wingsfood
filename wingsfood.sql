CREATE DATABASE nama_database;

USE wingsfood;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tambahkan user default
INSERT INTO users (username, password) VALUES ('admin', MD5('password123'));
INSERT INTO users (username, password) VALUES ('Operator', MD5('12345'));

CREATE TABLE input_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jumlah_produk INT NOT NULL,
    jenis_produk VARCHAR(100) NOT NULL,
    kode_batch VARCHAR(50) NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    gambar_produk VARCHAR(255) DEFAULT 'placeholder.png'
);

CREATE TABLE mesin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mesin VARCHAR(255) NOT NULL,
    status ENUM('Aktif', 'Rusak') NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    waktu_terakhir DATETIME NOT NULL
);