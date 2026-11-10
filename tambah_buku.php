<?php
require 'config/koneksi.php';
wajib_login();
$f = flash();
$buku = $_SESSION['old'] ?? []; unset($_SESSION['old']);
$aksi = 'proses_tambah.php';
$judul_form = 'Tambah Buku Baru';
head('Tambah Buku'); navbar();
if ($f): ?><div class="container" style="max-width:720px"><div class="alert alert-<?= e($f[0]) ?>"><?= e($f[1]) ?></div></div><?php endif;
require 'form_buku.php';
foot();
