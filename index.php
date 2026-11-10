<?php
require 'config/koneksi.php';
if (!empty($_SESSION['login'])) { header('Location: dashboard.php'); exit; }
$f = flash();
$ingat = $_COOKIE['remember_username'] ?? '';
head('Login'); ?>
<div class="min-vh-100 d-flex align-items-center brand">
  <div class="container" style="max-width:420px">
    <div class="card p-4">
      <div class="text-center mb-3">
        <i class="bi bi-book-half fs-1 text-primary"></i>
        <h4 class="fw-bold mt-2">Mini Digital Library</h4>
        <p class="text-muted small mb-0">Silakan login untuk melanjutkan</p>
      </div>
      <?php if ($f): ?><div class="alert alert-<?= e($f[0]) ?> py-2"><?= e($f[1]) ?></div><?php endif; ?>
      <form action="proses_login.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= e($ingat) ?>" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" <?= $ingat ? 'checked' : '' ?>>
          <label class="form-check-label" for="remember">Remember Me</label>
        </div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
      <p class="text-center text-muted small mt-3 mb-0">Demo: admin / admin123</p>
    </div>
  </div>
</div>
<?php foot();
