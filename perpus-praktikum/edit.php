<?php
require 'connection.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = ?");
$stmt->execute([$id]);
$buku = $stmt->fetch();
if (!$buku) { header("Location: index.php"); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul     = trim($_POST['judul']     ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $tahun     = trim($_POST['tahun']     ?? '');
    $genre     = trim($_POST['genre']     ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($judul === '')     $errors['judul']     = 'Judul tidak boleh kosong.';
    if ($pengarang === '') $errors['pengarang'] = 'Pengarang tidak boleh kosong.';
    if ($genre === '')     $errors['genre']     = 'Pilih genre buku.';
    if ($tahun !== '' && (!is_numeric($tahun) || $tahun < 1000 || $tahun > 2099))
                           $errors['tahun']     = 'Tahun tidak valid (1000–2099).';

    if (empty($errors)) {
        $sql  = "UPDATE buku SET judul=?, pengarang=?, tahun=?, genre=?, deskripsi=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $judul,
            $pengarang,
            $tahun !== '' ? (int)$tahun : null,
            $genre,
            $deskripsi ?: null,
            $id
        ]);

        header("Location: index.php?alert=edit");
        exit;
    }

    $buku = array_merge($buku, compact('judul','pengarang','tahun','genre','deskripsi'));
}

$pageTitle = 'Edit Buku';
require 'style.php';
?>

  <header>
    <div class="logo-block">
      <div class="logo-title">BOOKNEST</div>
  </header>

  <div class="form-page">
    <div class="page-heading">Edit Data Buku</div>
    <div class="page-sub">Mengubah: <em style="color:var(--accent)"><?= htmlspecialchars($buku['judul']) ?></em></div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">Harap perbaiki kesalahan di bawah sebelum menyimpan.</div>
    <?php endif; ?>

    <div class="form-card">
      <form method="POST" action="edit.php?id=<?= $id ?>">

        <div class="form-group">
          <label class="form-label">Judul Buku *</label>
          <input type="text" name="judul" class="form-input"
                 value="<?= htmlspecialchars($buku['judul']) ?>" required>
          <?php if (isset($errors['judul'])): ?>
            <small style="color:var(--red);font-size:0.75rem"><?= $errors['judul'] ?></small>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Pengarang *</label>
          <input type="text" name="pengarang" class="form-input"
                 value="<?= htmlspecialchars($buku['pengarang']) ?>" required>
          <?php if (isset($errors['pengarang'])): ?>
            <small style="color:var(--red);font-size:0.75rem"><?= $errors['pengarang'] ?></small>
          <?php endif; ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Tahun Terbit</label>
            <input type="number" name="tahun" class="form-input"
                   min="1000" max="2099"
                   value="<?= htmlspecialchars($buku['tahun'] ?? '') ?>">
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
                <option value="<?= $g ?>" <?= $buku['genre'] === $g ? 'selected' : '' ?>><?= $g ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['genre'])): ?>
              <small style="color:var(--red);font-size:0.75rem"><?= $errors['genre'] ?></small>
            <?php endif; ?>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Singkat</label>
          <textarea name="deskripsi" class="form-textarea"><?= htmlspecialchars($buku['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
          <a href="index.php" class="btn-cancel">Batal</a>
          <button type="submit" class="btn-save">Simpan Perubahan</button>
        </div>

      </form>
    </div>
  </div>

</div>
</body>
</html>
