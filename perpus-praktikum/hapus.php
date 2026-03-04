<?php
require 'connection.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM buku WHERE id = ?");
$stmt->execute([$id]);

if (!$stmt->fetch()) {
    header("Location: index.php");
    exit;
}

$del = $pdo->prepare("DELETE FROM buku WHERE id = ?");
$del->execute([$id]);

header("Location: index.php?alert=hapus");
exit;
