<?php
// Koneksi database MySQL (mysqli) - sesuaikan dengan environment XAMPP/Laragon Anda
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'perpustakaan_mini';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $koneksi = new mysqli($host, $user, $pass, $db);
    $koneksi->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Koneksi database gagal. Pastikan MySQL aktif dan database sudah diimport (database.sql).');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const MAX_UPLOAD = 2 * 1024 * 1024; // 2 MB
const UPLOAD_DIR = __DIR__ . '/../uploads/';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Hanya user yang sudah login boleh mengakses halaman terproteksi
function wajib_login() {
    if (empty($_SESSION['login'])) {
        header('Location: index.php');
        exit;
    }
}

// Logika bisnis: percabangan untuk label status ketersediaan
function label_status($stok) {
    if ($stok > 0) return ['Tersedia', 'success'];
    return ['Dipinjam', 'danger'];
}

// Daftar kategori (array)
function daftar_kategori() {
    return ['Novel', 'Sastra', 'Teknologi', 'Sains', 'Sejarah', 'Pendidikan', 'Lainnya'];
}

function flash($tipe = null, $pesan = null) {
    if ($tipe !== null) { $_SESSION['flash'] = [$tipe, $pesan]; return null; }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// Validasi & simpan file sampul. Return [nama_file|null, error|null]
function upload_sampul($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) return [null, null];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, $file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
            ? 'Ukuran file melebihi batas.' : 'Upload gagal (kode ' . $file['error'] . ').'];
    }
    if ($file['size'] > MAX_UPLOAD) return [null, 'Ukuran sampul maksimal 2MB.'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) return [null, 'Sampul harus berformat .jpg atau .png.'];

    // Validasi isi file sebenarnya (bukan hanya ekstensi)
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, ['image/jpeg', 'image/png'], true)) return [null, 'File bukan gambar JPG/PNG yang valid.'];

    $nama = 'sampul_' . bin2hex(random_bytes(8)) . '.' . ($ext === 'jpeg' ? 'jpg' : $ext);
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $nama)) return [null, 'Gagal menyimpan file ke server.'];
    return [$nama, null];
}

function hapus_sampul($nama) {
    if ($nama && is_file(UPLOAD_DIR . basename($nama))) unlink(UPLOAD_DIR . basename($nama));
}

// Validasi input buku. Return array error
function validasi_buku($d) {
    $err = [];
    if (trim($d['judul'] ?? '') === '') $err[] = 'Judul wajib diisi.';
    if (!in_array($d['kategori'] ?? '', daftar_kategori(), true)) $err[] = 'Kategori tidak valid.';
    if (trim($d['penulis'] ?? '') === '') $err[] = 'Penulis wajib diisi.';
    $thn = filter_var($d['tahun_terbit'] ?? '', FILTER_VALIDATE_INT);
    if ($thn === false || $thn < 1000 || $thn > (int)date('Y')) $err[] = 'Tahun terbit tidak valid.';
    $stok = filter_var($d['stok'] ?? '', FILTER_VALIDATE_INT);
    if ($stok === false || $stok < 0) $err[] = 'Stok harus berupa angka >= 0.';
    return $err;
}

function head($judul) { ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?> | Mini Digital Library</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { background:#f4f6fb; }
  .brand { background:linear-gradient(135deg,#4f46e5,#7c3aed); }
  .card { border:0; border-radius:14px; box-shadow:0 4px 18px rgba(30,30,80,.08); }
  .cover { width:52px; height:70px; object-fit:cover; border-radius:6px; background:#e5e7eb; }
  .cover-preview { max-height:180px; border-radius:8px; }
</style>
</head>
<body>
<?php }

function navbar() { ?>
<nav class="navbar navbar-dark brand mb-4">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="dashboard.php"><i class="bi bi-book-half me-2"></i>Mini Digital Library</a>
    <div class="text-white d-flex align-items-center gap-3">
      <span class="small"><i class="bi bi-person-circle me-1"></i><?= e($_SESSION['nama'] ?? '') ?></span>
      <a href="logout.php" class="btn btn-sm btn-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>
</nav>
<?php }

function foot() { ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
<?php }
