<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Controllers/SimulationController.php';

$db = getDbConnection();
$controller = new SimulationController($db);
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($action === 'get_question') {
    $questionId = (int) ($_GET['id'] ?? 1);
    echo json_encode($controller->getQuestion($questionId), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'submit_choice') {
    $questionId = (int) ($_POST['id_ai_hoi'] ?? 0);
    $choice = trim((string) ($_POST['lua_chon'] ?? ''));
    echo json_encode($controller->submitChoice($questionId, $choice), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'get_danh_gia') {
    $roomId = isset($_GET['room_id']) && $_GET['room_id'] !== ''
        ? (int) $_GET['room_id']
        : null;

    echo json_encode($controller->getRoomReview($roomId), JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(400);
echo json_encode([
    'error' => 'Hành động không hợp lệ',
], JSON_UNESCAPED_UNICODE);
