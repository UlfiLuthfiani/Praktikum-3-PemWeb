<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Buku.php';

$database = new Database();
$db       = $database->getConnection();

$buku    = new Buku($db);
$genre   = $_GET['genre'] ?? '';
$keyword = $_GET['q']     ?? '';

$stmt = $buku->read($genre, $keyword); 
$num  = $stmt->rowCount();

if ($num > 0) {
    $buku_arr = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $buku_item = array(           
            "id"        => $row['id'],
            "judul"     => $row['judul'],
            "pengarang" => $row['pengarang'],
            "tahun"     => $row['tahun'],
            "genre"     => $row['genre'],
            "deskripsi" => $row['deskripsi']
        );
        array_push($buku_arr, $buku_item);
    }

    http_response_code(200);
    echo json_encode($buku_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Data tidak ditemukan."));
}
?>