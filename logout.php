<?php
require 'config/koneksi.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();
setcookie('remember_username', '', time() - 3600, '/'); // hapus cookie Remember Me

session_start();
flash('success', 'Anda berhasil logout.');
header('Location: index.php');
