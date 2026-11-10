<?php
// Partial form (dipakai tambah_buku.php & edit_buku.php). Variabel: $aksi, $buku, $judul_form
?>
<div class="container pb-5" style="max-width:720px">
  <div class="card p-4">
    <h5 class="fw-bold mb-3"><?= e($judul_form) ?></h5>
    <form action="<?= e($aksi) ?>" method="POST" enctype="multipart/form-data">
      <?php if (!empty($buku['id'])): ?><input type="hidden" name="id" value="<?= (int)$buku['id'] ?>"><?php endif; ?>
      <div class="mb-3">
        <label class="form-label">Judul Buku</label>
        <input type="text" name="judul" class="form-control" value="<?= e($buku['judul'] ?? '') ?>" required>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Kategori</label>
          <select name="kategori" class="form-select" required>
            <?php foreach (daftar_kategori() as $k): ?>
              <option <?= ($buku['kategori'] ?? '') === $k ? 'selected' : '' ?>><?= e($k) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Penulis</label>
          <input type="text" name="penulis" class="form-control" value="<?= e($buku['penulis'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Tahun Terbit</label>
          <input type="number" name="tahun_terbit" class="form-control" min="1000" max="<?= date('Y') ?>" value="<?= e($buku['tahun_terbit'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Stok (status ketersediaan)</label>
          <input type="number" name="stok" class="form-control" min="0" value="<?= e($buku['stok'] ?? 1) ?>" required>
          <div class="form-text">Stok &gt; 0 = Tersedia, 0 = Dipinjam.</div>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Sampul Buku (.jpg/.png, maks 2MB)</label>
        <?php if (!empty($buku['sampul'])): ?>
          <div class="mb-2"><img src="uploads/<?= e($buku['sampul']) ?>" class="cover-preview" alt="Sampul saat ini"><div class="form-text">Sampul saat ini. Pilih file baru untuk menggantinya.</div></div>
        <?php endif; ?>
        <input type="file" name="sampul" id="sampul" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
        <img id="preview" class="cover-preview mt-2 d-none" alt="Preview">
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<script>
document.getElementById('sampul').addEventListener('change', function () {
  var f = this.files[0], p = document.getElementById('preview');
  if (!f) { p.classList.add('d-none'); return; }
  if (f.size > 2 * 1024 * 1024) { alert('Ukuran file maksimal 2MB.'); this.value = ''; p.classList.add('d-none'); return; }
  p.src = URL.createObjectURL(f); p.classList.remove('d-none');
});
</script>
