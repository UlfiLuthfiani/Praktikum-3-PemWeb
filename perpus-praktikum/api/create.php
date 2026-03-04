<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Method tidak diizinkan."]);
    exit;
}

include_once '../config/Database.php';
include_once '../models/Buku.php';

$database = new Database();
$db       = $database->getConnection();

$buku = new Buku($db);
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->judul) &&
    !empty($data->pengarang) &&
    !empty($data->genre)
) {
    $buku->judul     = $data->judul;
    $buku->pengarang = $data->pengarang;
    $buku->tahun     = $data->tahun     ?? null;
    $buku->genre     = $data->genre;
    $buku->deskripsi = $data->deskripsi ?? null;

    if ($buku->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Buku berhasil ditambahkan."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Gagal menambahkan buku."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Data tidak lengkap. judul, pengarang, dan genre wajib diisi."));
}
?>
