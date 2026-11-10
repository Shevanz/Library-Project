<?php
require 'config/koneksi.php';
wajib_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }

$id = (int)($_POST['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT sampul FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$lama = $stmt->get_result()->fetch_assoc();
if (!$lama) { flash('danger', 'Data buku tidak ditemukan.'); header('Location: dashboard.php'); exit; }

$err = validasi_buku($_POST);
[$sampulBaru, $errUpload] = upload_sampul($_FILES['sampul'] ?? null);
if ($errUpload) $err[] = $errUpload;

if ($err) {
    hapus_sampul($sampulBaru);
    flash('danger', implode(' ', $err));
    header("Location: edit_buku.php?id=$id");
    exit;
}

$judul = trim($_POST['judul']); $kategori = $_POST['kategori']; $penulis = trim($_POST['penulis']);
$tahun = (int)$_POST['tahun_terbit']; $stok = (int)$_POST['stok'];
$sampul = $sampulBaru ?? $lama['sampul']; // pertahankan sampul lama jika tidak ada upload baru

$stmt = $koneksi->prepare('UPDATE buku SET judul=?, kategori=?, penulis=?, tahun_terbit=?, stok=?, sampul=? WHERE id=?');
$stmt->bind_param('sssiisi', $judul, $kategori, $penulis, $tahun, $stok, $sampul, $id);
$stmt->execute();

if ($sampulBaru) hapus_sampul($lama['sampul']); // hapus file lama setelah update sukses

flash('success', 'Data buku berhasil diperbarui.');
header('Location: dashboard.php');
