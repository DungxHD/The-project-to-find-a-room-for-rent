<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/SearchController.php';

$db = getDbConnection();
$controller = new SearchController($db);

// Chấp nhận cả GET (tiện test trên trình duyệt) và POST (dùng trong form thật)
$input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

try {
    $result = $controller->handle($input);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Không thể thực hiện tìm kiếm.',
    ], JSON_UNESCAPED_UNICODE);
}