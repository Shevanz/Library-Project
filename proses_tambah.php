<?php
require 'config/koneksi.php';
wajib_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: tambah_buku.php'); exit; }

$err = validasi_buku($_POST);
[$sampul, $errUpload] = upload_sampul($_FILES['sampul'] ?? null);
if ($errUpload) $err[] = $errUpload;

if ($err) {
    hapus_sampul($sampul);
    $_SESSION['old'] = $_POST;
    flash('danger', implode(' ', $err));
    header('Location: tambah_buku.php');
    exit;
}

$judul = trim($_POST['judul']); $kategori = $_POST['kategori']; $penulis = trim($_POST['penulis']);
$tahun = (int)$_POST['tahun_terbit']; $stok = (int)$_POST['stok'];

$stmt = $koneksi->prepare('INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok, sampul) VALUES (?,?,?,?,?,?)');
$stmt->bind_param('sssiis', $judul, $kategori, $penulis, $tahun, $stok, $sampul);
$stmt->execute();

flash('success', 'Buku berhasil ditambahkan.');
header('Location: dashboard.php');
