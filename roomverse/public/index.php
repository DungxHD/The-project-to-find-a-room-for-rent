<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Controllers/RoomPortalController.php';

$db = getDbConnection();
$controller = new RoomPortalController($db);

$requestedPage = (string) ($_GET['page'] ?? ($_GET['action'] ?? 'home'));
$currentPage = in_array($requestedPage, ['home', 'admin', 'detail', 'vr'], true)
    ? $requestedPage
    : 'home';

$status = (string) ($_GET['status'] ?? '');
$flashMessage = match ($status) {
    'created' => 'Đã thêm phòng trọ mới thành công.',
    'updated' => 'Đã cập nhật phòng trọ thành công.',
    'deleted' => 'Đã xóa phòng trọ thành công.',
    default => '',
};

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentPage === 'admin') {
    $formAction = (string) ($_POST['form_action'] ?? '');

    try {
        if ($formAction === 'create_room') {
            $controller->createRoom($_POST, $_FILES);
            header('Location: index.php?page=admin&status=created');
            exit;
        }

        if ($formAction === 'update_room') {
            $roomId = (int) ($_POST['room_id'] ?? 0);
            $controller->updateRoom($roomId, $_POST, $_FILES);
            header('Location: index.php?page=admin&status=updated&edit=' . $roomId);
            exit;
        }

        if ($formAction === 'delete_room') {
            $roomId = (int) ($_POST['room_id'] ?? 0);
            $controller->deleteRoom($roomId);
            header('Location: index.php?page=admin&status=deleted');
            exit;
        }
    } catch (Throwable $e) {
        $errorMessage = $e->getMessage();
    }
}

if ($currentPage === 'admin') {
    $editingRoomId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
    $adminPageData = $controller->getAdminPageData($editingRoomId);
    $stats = $adminPageData['stats'];
    $adminRooms = $adminPageData['rooms'];
    $formOptions = $adminPageData['formOptions'];
    $editingRoom = $adminPageData['editingRoom'];

    include __DIR__ . '/../app/Views/portal/admin.php';
    return;
}

if ($currentPage === 'detail') {
    $roomId = (int) ($_GET['id'] ?? 0);
    $room = $roomId > 0 ? $controller->getRoomDetailPageData($roomId) : null;

    include __DIR__ . '/../app/Views/portal/detail.php';
    return;
}

if ($currentPage === 'vr') {
    $roomId = (int) ($_GET['id'] ?? 0);
    $room = $roomId > 0 ? $controller->getRoomDetailPageData($roomId) : null;

    include __DIR__ . '/../app/Views/portal/vr.php';
    return;
}

$pageData = $controller->getHomePageData();
$doiTuongList = $pageData['doiTuongList'];
$khuVucList = $pageData['khuVucList'];
$rooms = $pageData['rooms'];
$heroImages = $pageData['heroImages'];

include __DIR__ . '/../app/Views/home/index.php';
