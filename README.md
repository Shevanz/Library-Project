# Mini Digital Library

Aplikasi backend **Perpustakaan Digital Mini** untuk mengelola data buku, dibangun dengan **PHP Native** (tanpa framework) dan **MySQL**. Proyek ini dibuat sebagai Tugas Terstruktur mata kuliah pemrograman web, mencakup autentikasi (session & cookie), CRUD, upload file, serta validasi data.

## Fitur

### Autentikasi & Hak Akses
- **Login** dengan form `POST` (username & password). Password disimpan dalam bentuk hash (`password_hash` / `password_verify`).
- **Session**: hanya pengguna yang sudah login yang dapat mengakses dashboard dan mengelola data buku.
- **Remember Me**: menyimpan username di cookie browser selama 7 hari.
- **Logout**: menghapus session dan cookie, lalu mengarahkan kembali ke halaman login.

### Pengelolaan Data Buku (CRUD)
| Operasi | Keterangan |
|---------|------------|
| **Create** | Tambah buku: judul, kategori, penulis, tahun terbit, stok, dan sampul |
| **Read** | Daftar buku dalam tabel HTML, lengkap dengan statistik dan pencarian |
| **Update** | Ubah data buku dan (opsional) ganti file sampul |
| **Delete** | Hapus data dari database sekaligus menghapus file sampul dari folder `uploads/` |

### Upload Sampul Buku
- Format yang diterima: `.jpg`, `.jpeg`, `.png`
- Ukuran maksimal **2 MB**
- Validasi ekstensi dan isi file (MIME type), nama file diacak untuk keamanan
- Nama file disimpan di database, file disimpan di folder `uploads/`

### Logika Bisnis & Validasi
- Label status ketersediaan memakai percabangan: **stok > 0 → "Tersedia"**, **stok = 0 → "Dipinjam"**
- Validasi input: judul/penulis wajib, tahun terbit valid, stok angka ≥ 0, kategori sesuai daftar

## Teknologi
- PHP 8+ (Native) dengan ekstensi `mysqli` (prepared statement)
- MySQL / MariaDB
- Bootstrap 5 & Bootstrap Icons (via CDN)
- JavaScript (konfirmasi hapus, preview gambar, validasi ukuran file di sisi klien)

## Struktur Project

```
project_perpustakaan/
├── config/
│   └── koneksi.php        # Koneksi MySQL, session, dan fungsi bantu
├── uploads/               # Folder penampung sampul buku
├── index.php              # Halaman login
├── proses_login.php       # Verifikasi autentikasi, session & cookie
├── logout.php             # Hapus session & cookie
├── dashboard.php          # Tampil data (Read) & menu utama
├── tambah_buku.php        # Form tambah data
├── proses_tambah.php      # Query insert & upload file
├── edit_buku.php          # Form edit data
├── proses_edit.php        # Query update & penggantian file
├── hapus_buku.php         # Query delete & unlink file gambar
├── form_buku.php          # Form bersama (dipakai tambah & edit)
├── database.sql           # Skema database & data awal
└── README.md
```

## Struktur Database

Database: `perpustakaan_mini`

**Tabel `users`**: `id`, `username`, `password` (hash), `nama_lengkap`

**Tabel `buku`**: `id`, `judul`, `kategori`, `penulis`, `tahun_terbit`, `stok`, `sampul`, `created_at`

## Cara Menjalankan

1. Pastikan **XAMPP** atau **Laragon** sudah terpasang, lalu aktifkan Apache dan MySQL.
2. Salin folder `project_perpustakaan` ke `htdocs` (XAMPP) atau `www` (Laragon).
3. Buka phpMyAdmin dan import file `database.sql`.
4. Jika perlu, sesuaikan kredensial database di `config/koneksi.php`:
   ```php
   $host = 'localhost';
   $user = 'root';
   $pass = '';
   $db   = 'perpustakaan_mini';
   ```
5. Buka di browser: `http://localhost/project_perpustakaan/`

### Akun Demo
| Username | Password |
|----------|----------|
| `admin`  | `admin123` |

> Catatan: Tampilan memakai Bootstrap dari CDN sehingga membutuhkan koneksi internet. Pastikan folder `uploads/` dapat ditulis (writable) oleh server.

## Catatan Keamanan
- Query memakai *prepared statement* (mencegah SQL Injection)
- Output di-escape dengan `htmlspecialchars` (mencegah XSS)
- `session_regenerate_id` saat login, cookie `HttpOnly` dan `SameSite`
- Eksekusi script dinonaktifkan di folder `uploads/` lewat `.htaccess`

