<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

$buku->id        = $data->id;
$buku->judul     = $data->judul;
$buku->pengarang = $data->pengarang;
$buku->tahun     = $data->tahun     ?? null;
$buku->genre     = $data->genre;
$buku->deskripsi = $data->deskripsi ?? null;

if ($buku->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Data buku berhasil diperbarui."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Gagal memperbarui data."));
}
?>
