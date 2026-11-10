<?php
require 'config/koneksi.php';
wajib_login();

$cari = trim($_GET['cari'] ?? '');
if ($cari !== '') {
    $like = "%$cari%";
    $stmt = $koneksi->prepare('SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? OR kategori LIKE ? ORDER BY id DESC');
    $stmt->bind_param('sss', $like, $like, $like);
    $stmt->execute();
    $hasil = $stmt->get_result();
} else {
    $hasil = $koneksi->query('SELECT * FROM buku ORDER BY id DESC');
}
$data = $hasil->fetch_all(MYSQLI_ASSOC);
$total = count($data);
$tersedia = count(array_filter($data, fn($b) => $b['stok'] > 0));
$f = flash();

head('Dashboard'); navbar(); ?>
<div class="container pb-5">
  <?php if ($f): ?>
    <div class="alert alert-<?= e($f[0]) ?> alert-dismissible fade show"><?= e($f[1]) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4"><div class="card p-3"><div class="text-muted small">Total Buku</div><div class="fs-3 fw-bold"><?= $total ?></div></div></div>
    <div class="col-6 col-md-4"><div class="card p-3"><div class="text-muted small">Tersedia</div><div class="fs-3 fw-bold text-success"><?= $tersedia ?></div></div></div>
    <div class="col-12 col-md-4"><div class="card p-3"><div class="text-muted small">Dipinjam</div><div class="fs-3 fw-bold text-danger"><?= $total - $tersedia ?></div></div></div>
  </div>

  <div class="card p-3">
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
      <form class="d-flex gap-2" method="GET">
        <input type="search" name="cari" class="form-control" placeholder="Cari judul/penulis/kategori" value="<?= e($cari) ?>">
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
      </form>
      <a href="tambah_buku.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Buku</a>
    </div>
    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr><th>#</th><th>Sampul</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tahun</th><th>Stok</th><th>Status</th><th class="text-end">Aksi</th></tr>
        </thead>
        <tbody>
        <?php if (!$data): ?>
          <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data buku.</td></tr>
        <?php endif; ?>
        <?php foreach ($data as $i => $b): [$label, $warna] = label_status($b['stok']); ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>
              <?php if ($b['sampul']): ?>
                <img class="cover" src="uploads/<?= e($b['sampul']) ?>" alt="Sampul">
              <?php else: ?>
                <div class="cover d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></div>
              <?php endif; ?>
            </td>
            <td class="fw-semibold"><?= e($b['judul']) ?></td>
            <td><?= e($b['kategori']) ?></td>
            <td><?= e($b['penulis']) ?></td>
            <td><?= e($b['tahun_terbit']) ?></td>
            <td><?= (int)$b['stok'] ?></td>
            <td><span class="badge text-bg-<?= $warna ?>"><?= $label ?></span></td>
            <td class="text-end text-nowrap">
              <a href="edit_buku.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <a href="hapus_buku.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger btn-hapus" data-judul="<?= e($b['judul']) ?>"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
document.querySelectorAll('.btn-hapus').forEach(function (a) {
  a.addEventListener('click', function (ev) {
    if (!confirm('Hapus buku "' + a.dataset.judul + '"? Tindakan ini tidak dapat dibatalkan.')) ev.preventDefault();
  });
});
</script>
<?php foot();
