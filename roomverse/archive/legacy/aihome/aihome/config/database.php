<?php
/**
 * Cấu hình kết nối CSDL (mysqli)
 * Sửa 4 hằng số bên dưới cho đúng với môi trường của bạn.
 */
declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aihome');

/**
 * Trả về một kết nối mysqli dùng chung (singleton) cho toàn bộ request.
 */
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
                'message' => 'Không thể kết nối cơ sở dữ liệu.',
            ]);
            exit;
        }
    }

    return $conn;
}
