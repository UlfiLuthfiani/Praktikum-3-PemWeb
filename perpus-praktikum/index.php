<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'connection.php';

$keyword = trim($_GET['q'] ?? '');
$genre   = $_GET['genre'] ?? '';

$sql    = "SELECT * FROM buku WHERE 1=1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND (judul LIKE ? OR pengarang LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
if ($genre !== '') {
    $sql .= " AND genre = ?";
    $params[] = $genre;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

$totalBuku  = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalGenre = $pdo->query("SELECT COUNT(DISTINCT genre) FROM buku")->fetchColumn();

$alert = $_GET['alert'] ?? '';
$pageTitle = 'Koleksi Buku';
require 'style.php';
?>

<header>
    <div class="logo-block">
        <div class="logo-title">BOOKNEST</div>
    </div>
    <div class="header-stats">
        <div class="stat-item">
            <div class="stat-num"><?= $totalBuku ?></div>
            <div class="stat-label">Total Buku</div>
        </div>
        <div class="stat-item">
            <div class="stat-num"><?= $totalGenre ?></div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>
</header>

<?php if ($alert === 'tambah'): ?>
    <div class="alert alert-success">Buku baru berhasil ditambahkan!</div>
<?php elseif ($alert === 'edit'): ?>
    <div class="alert alert-success">Data buku berhasil diperbarui!</div>
<?php elseif ($alert === 'hapus'): ?>
    <div class="alert alert-success">Buku berhasil dihapus.</div>
<?php endif; ?>

<form method="GET" action="index.php">
    <div class="toolbar">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari judul atau pengarang...">
        </div>
        <select name="genre" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Genre</option>
            <?php
            $genres = ['Fiksi','Non-Fiksi','Sains','Sejarah','Teknologi','Lainnya'];
            foreach ($genres as $g):
            ?>
                <option value="<?= $g ?>" <?= $genre === $g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-add" style="background:var(--surface2);color:var(--text);border:1px solid var(--border);">🔍 Cari</button>
        <a href="tambah.php" class="btn-add">＋ Tambah Buku</a>
    </div>
</form>

<div class="books-grid">
    <?php if (empty($books)): ?>
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Tidak ada buku ditemukan</h3>
            <p>Coba ubah kata kunci pencarian atau <a href="tambah.php" style="color:var(--accent)">tambahkan buku baru</a>.</p>
        </div>
    <?php else: ?>
        <?php foreach ($books as $i => $b): ?>
            <div class="book-card" style="animation-delay:<?= $i * 0.05 ?>s">
                <span class="card-genre genre-<?= htmlspecialchars($b['genre']) ?>">
                    <?= htmlspecialchars($b['genre']) ?>
                </span>
                <div class="book-title"><?= htmlspecialchars($b['judul']) ?></div>
                <div class="book-author">oleh <?= htmlspecialchars($b['pengarang']) ?></div>
                <div class="book-meta">
                    <div class="meta-row">
                        <span class="meta-key">Tahun</span>
                        <span class="meta-val"><?= htmlspecialchars($b['tahun'] ?: '—') ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-key">Genre</span>
                        <span class="meta-val"><?= htmlspecialchars($b['genre']) ?></span>
                    </div>
                    <?php if ($b['deskripsi']): ?>
                        <div class="meta-row" style="flex-direction:column;align-items:flex-start;gap:0.3rem;margin-top:0.3rem">
                            <span class="meta-key">Deskripsi</span>
                            <span class="meta-val" style="font-size:0.75rem;color:var(--muted);line-height:1.5">
                                <?= htmlspecialchars(substr($b['deskripsi'], 0, 90)) ?>
                                <?= strlen($b['deskripsi']) > 90 ? '…' : '' ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-actions">
                    <a href="edit.php?id=<?= $b['id'] ?>" class="btn-edit">Edit</a>
                    <a href="hapus.php?id=<?= $b['id'] ?>"
                       class="btn-del"
                       onclick="return confirm('Hapus buku &quot;<?= htmlspecialchars(addslashes($b['judul'])) ?>&quot;?')">
                        Hapus
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
