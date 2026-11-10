<?php
require 'config/koneksi.php';
wajib_login();
$id = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT * FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$buku = $stmt->get_result()->fetch_assoc();
if (!$buku) { flash('danger', 'Data buku tidak ditemukan.'); header('Location: dashboard.php'); exit; }

$f = flash();
$aksi = 'proses_edit.php';
$judul_form = 'Edit Buku';
head('Edit Buku'); navbar();
if ($f): ?><div class="container" style="max-width:720px"><div class="alert alert-<?= e($f[0]) ?>"><?= e($f[1]) ?></div></div><?php endif;
require 'form_buku.php';
foot();
