CREATE DATABASE IF NOT EXISTS perpustakaan_mini CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE perpustakaan_mini;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS buku (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  tahun_terbit YEAR NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  sampul VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Login awal: admin / admin123
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$t72DsYrCoYF8G8HAV4HrCOvIPGEjVOvIaCalCdVof6QXBJs.ZWYo2', 'Administrator');

INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok) VALUES
('Laskar Pelangi', 'Novel', 'Andrea Hirata', 2005, 3),
('Algoritma dan Pemrograman', 'Teknologi', 'Rinaldi Munir', 2016, 0),
('Bumi Manusia', 'Sastra', 'Pramoedya Ananta Toer', 1980, 2);
