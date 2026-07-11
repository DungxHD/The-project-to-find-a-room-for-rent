<?php

declare(strict_types=1);

/**
 * Cấu hình kết nối CSDL dùng chung cho toàn bộ RoomVerse.
 *
 * Da sua:
 * - Gộp 2 database cũ thành một database duy nhất tên `roomverse`.
 * - Giữ cơ chế singleton để mọi request chỉ mở 1 kết nối mysqli.
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'roomverse');

function getDbConnection(): mysqli
{
    static $conn = null;

    if ($conn === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $conn->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Không thể kết nối cơ sở dữ liệu RoomVerse.',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    return $conn;
}
