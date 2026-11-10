<?php
require 'config/koneksi.php';
wajib_login();
$id = (int)($_GET['id'] ?? 0);

$stmt = $koneksi->prepare('SELECT sampul FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$buku = $stmt->get_result()->fetch_assoc();

if ($buku) {
    $del = $koneksi->prepare('DELETE FROM buku WHERE id = ?');
    $del->bind_param('i', $id);
    $del->execute();
    hapus_sampul($buku['sampul']); // unlink file gambar
    flash('success', 'Buku berhasil dihapus.');
} else {
    flash('danger', 'Data buku tidak ditemukan.');
}
header('Location: dashboard.php');
