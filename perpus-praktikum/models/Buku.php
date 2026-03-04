<?php
class Buku {
    private $conn;
    private $table_name = "buku";

    public $id;
    public $judul;
    public $pengarang;
    public $tahun;
    public $genre;
    public $deskripsi;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($genre = '', $keyword = '') {
    $sql    = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
    $params = [];

    if ($genre !== '') {
        $sql     .= " AND genre = ?";
        $params[] = $genre;
    }
    if ($keyword !== '') {
        $sql     .= " AND (judul LIKE ? OR pengarang LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }

    $sql .= " ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (judul, pengarang, tahun, genre, deskripsi)
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([
            $this->judul,
            $this->pengarang,
            $this->tahun,
            $this->genre,
            $this->deskripsi
        ])) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET judul = ?, pengarang = ?, tahun = ?, genre = ?, deskripsi = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute([
            $this->judul,
            $this->pengarang,
            $this->tahun,
            $this->genre,
            $this->deskripsi,
            $this->id
        ])) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt  = $this->conn->prepare($query);
        if ($stmt->execute([$this->id])) {
            return true;
        }
        return false;
    }
}
?>
