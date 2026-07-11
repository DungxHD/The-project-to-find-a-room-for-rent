<?php
// =========================================================
// KET NOI DATABASE (mysqli)
// Chinh lai thong tin ben duoi cho phu hop voi may chu cua ban
// =========================================================
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "mo_phong_songthu";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(["error" => "Ket noi database that bai: " . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($conn, "utf8mb4");
