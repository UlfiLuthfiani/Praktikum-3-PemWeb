<?php
require 'connection.php';

$errors = [];
$old    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul     = trim($_POST['judul']     ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $tahun     = trim($_POST['tahun']     ?? '');
    $genre     = trim($_POST['genre']     ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $old = compact('judul','pengarang','tahun','genre','deskripsi');

    if ($judul === '')     $errors['judul']     = 'Judul tidak boleh kosong.';
    if ($pengarang === '') $errors['pengarang'] = 'Pengarang tidak boleh kosong.';
    if ($genre === '')     $errors['genre']     = 'Pilih genre buku.';
    if ($tahun !== '' && (!is_numeric($tahun) || $tahun < 1000 || $tahun > 2099))
                           $errors['tahun']     = 'Tahun tidak valid (1000–2099).';

    if (empty($errors)) {
        $sql  = "INSERT INTO buku (judul, pengarang, tahun, genre, deskripsi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $judul,
            $pengarang,
            $tahun !== '' ? (int)$tahun : null,
            $genre,
            $deskripsi ?: null
        ]);

        header("Location: index.php?alert=tambah");
        exit;
    }
}

$pageTitle = 'Tambah Buku';
require 'style.php';
?>

  <header>
    <div class="logo-block">
      <div class="logo-eyebrow">Tambah Koleksi</div>
      <div class="logo-title">BOOKNEST</div>
    </div>
  </header>

  <div class="form-page">
    <div class="page-heading">Tambah Buku Baru</div>
    <div class="page-sub">Isi semua field yang bertanda * wajib diisi.</div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error"> Harap perbaiki kesalahan di bawah sebelum menyimpan.</div>
    <?php endif; ?>

    <div class="form-card">
      <form method="POST" action="tambah.php">

        <div class="form-group">
          <label class="form-label">Judul Buku *</label>
          <input type="text" name="judul" class="form-input"
                 placeholder="Masukkan judul buku"
                 value="<?= htmlspecialchars($old['judul'] ?? '') ?>" required>
          <?php if (isset($errors['judul'])): ?>
            <small style="color:var(--red);font-size:0.75rem"><?= $errors['judul'] ?></small>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Pengarang *</label>
          <input type="text" name="pengarang" class="form-input"
                 placeholder="Nama pengarang"
                 value="<?= htmlspecialchars($old['pengarang'] ?? '') ?>" required>
          <?php if (isset($errors['pengarang'])): ?>
            <small style="color:var(--red);font-size:0.75rem"><?= $errors['pengarang'] ?></small>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tahun Terbit</label>
            <input type="number" name="tahun" class="form-input"
                   placeholder="2024" min="1000" max="2099"
                   value="<?= htmlspecialchars($old['tahun'] ?? '') ?>">
            <?php if (isset($errors['tahun'])): ?>
              <small style="color:var(--red);font-size:0.75rem"><?= $errors['tahun'] ?></small>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label class="form-label">Genre *</label>
            <select name="genre" class="form-select" required>
              <option value="">— Pilih genre —</option>
              <?php
              $genres = ['Fiksi','Non-Fiksi','Sains','Sejarah','Teknologi','Lainnya'];
              foreach ($genres as $g):
              ?>
                <option value="<?= $g ?>" <?= ($old['genre'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['genre'])): ?>
              <small style="color:var(--red);font-size:0.75rem"><?= $errors['genre'] ?></small>
            <?php endif; ?>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Singkat</label>
          <textarea name="deskripsi" class="form-textarea"
                    placeholder="Ringkasan isi buku..."><?= htmlspecialchars($old['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
          <a href="index.php" class="btn-cancel">Batal</a>
          <button type="submit" class="btn-save">Simpan Buku</button>
        </div>

      </form>
    </div>
  </div>

</div>
</body>
</html>
