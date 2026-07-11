<?php

declare(strict_types=1);

require_once __DIR__ . '/HomeController.php';
require_once __DIR__ . '/../Models/RoomRepository.php';

class RoomPortalController
{
    private const AMENITY_KEYS = [
        'wifi',
        'dieu_hoa',
        'may_lanh',
        'may_giat',
        'may_say',
        'tu_lanh',
        'giuong',
        'tu_quan_ao',
        'ban_hoc',
        'ghe',
        'rem_cua',
        'ke_bep',
        'may_nuoc_nong',
    ];

    private const VR_SCENE_FIELDS = [
        'vr_living_room' => 'Phòng khách',
        'vr_kitchen' => 'Phòng bếp',
        'vr_bedroom' => 'Phòng ngủ',
    ];

    private mysqli $db;
    private HomeController $homeController;
    private RoomRepository $roomRepository;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
        $this->homeController = new HomeController($db);
        $this->roomRepository = new RoomRepository($db);
    }

    public function getHomePageData(): array
    {
        return $this->homeController->getHomePageData();
    }

    public function getAdminPageData(?int $editingRoomId = null): array
    {
        return [
            'stats' => $this->roomRepository->getAdminStats(),
            'rooms' => $this->roomRepository->getAdminRooms(),
            'formOptions' => $this->roomRepository->getAdminFormOptions(),
            'editingRoom' => $editingRoomId ? $this->roomRepository->getRoomDetail($editingRoomId) : null,
        ];
    }

    public function getRoomDetailPageData(int $roomId): ?array
    {
        return $this->roomRepository->getRoomDetail($roomId);
    }

    public function createRoom(array $post, array $files): int
    {
        $payload = $this->buildPayload($post, $files);

        return $this->roomRepository->createRoom($payload);
    }

    public function updateRoom(int $roomId, array $post, array $files): void
    {
        $payload = $this->buildPayload($post, $files);
        $this->roomRepository->updateRoom($roomId, $payload);
    }

    public function deleteRoom(int $roomId): void
    {
        $this->roomRepository->deleteRoom($roomId);
    }

    private function buildPayload(array $post, array $files): array
    {
        $roomName = trim((string) ($post['room_name'] ?? ''));
        $roomCode = trim((string) ($post['room_code'] ?? ''));

        if ($roomName === '') {
            throw new RuntimeException('Vui lòng nhập tên phòng trọ.');
        }

        if ($roomCode === '') {
            throw new RuntimeException('Vui lòng nhập mã phòng.');
        }

        $targetGroupId = (int) ($post['target_group_id'] ?? 0);

        if ($targetGroupId <= 0) {
            throw new RuntimeException('Vui lòng chọn đối tượng phù hợp.');
        }

        $status = (string) ($post['status'] ?? 'con_trong');
        if (!in_array($status, ['con_trong', 'da_thue'], true)) {
            $status = 'con_trong';
        }

        $gender = (string) ($post['gender'] ?? 'tat_ca');
        if (!in_array($gender, ['nam', 'nu', 'tat_ca'], true)) {
            $gender = 'tat_ca';
        }

        $imagePaths = $this->storeMultipleUploads($files['room_images'] ?? null, 'uploads/rooms');
        $vrScenePaths = [];

        foreach (self::VR_SCENE_FIELDS as $field => $sceneName) {
            $vrScenePaths[$sceneName] = $this->storeSingleUpload($files[$field] ?? null, 'uploads/vr');
        }

        $amenities = [];
        foreach (self::AMENITY_KEYS as $amenityKey) {
            $amenities[$amenityKey] = isset($post['amenities'][$amenityKey]) ? 1 : 0;
        }

        return [
            'room_name' => $roomName,
            'room_code' => $roomCode,
            'price' => $this->toFloat($post['price'] ?? null),
            'area' => $this->toFloat($post['area'] ?? null),
            'deposit' => $this->toFloat($post['deposit'] ?? null),
            'max_people' => max(1, (int) ($post['max_people'] ?? 1)),
            'status' => $status,
            'target_group_id' => $targetGroupId,
            'gender' => $gender,
            'description' => $this->nullIfEmpty($post['description'] ?? null),
            'location_name' => $this->fallbackText($post['location_name'] ?? null),
            'address' => $this->fallbackText($post['address'] ?? null),
            'latitude' => $this->toFloat($post['latitude'] ?? null),
            'longitude' => $this->toFloat($post['longitude'] ?? null),
            'google_maps_url' => $this->nullIfEmpty($post['google_maps_url'] ?? null),
            'electricity_price' => $this->toInt($post['electricity_price'] ?? null),
            'water_price' => $this->toInt($post['water_price'] ?? null),
            'extra_utilities' => $this->fallbackText($post['extra_utilities'] ?? null, 'Không có'),
            'overall_review' => $this->fallbackText($post['overall_review'] ?? null),
            'amenities' => $amenities,
            'image_paths' => $imagePaths,
            'vr_scene_paths' => $vrScenePaths,
            'remove_image_ids' => $this->normalizeIdArray($post['remove_image_ids'] ?? []),
            'remove_vr_ids' => $this->normalizeIdArray($post['remove_vr_ids'] ?? []),
        ];
    }

    private function storeMultipleUploads($fileBag, string $relativeDirectory): array
    {
        if (!is_array($fileBag) || !isset($fileBag['name']) || !is_array($fileBag['name'])) {
            return [];
        }

        $storedFiles = [];

        foreach (array_keys($fileBag['name']) as $index) {
            $singleFile = [
                'name' => $fileBag['name'][$index] ?? '',
                'type' => $fileBag['type'][$index] ?? '',
                'tmp_name' => $fileBag['tmp_name'][$index] ?? '',
                'error' => $fileBag['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $fileBag['size'][$index] ?? 0,
            ];

            $storedPath = $this->storeSingleUpload($singleFile, $relativeDirectory);
            if ($storedPath !== '') {
                $storedFiles[] = $storedPath;
            }
        }

        return $storedFiles;
    }

    private function storeSingleUpload($fileInfo, string $relativeDirectory): string
    {
        if (!is_array($fileInfo) || !isset($fileInfo['error'])) {
            return '';
        }

        $errorCode = (int) $fileInfo['error'];

        if ($errorCode === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        if ($errorCode !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Tải tệp lên thất bại, vui lòng thử lại.');
        }

        $originalName = (string) ($fileInfo['name'] ?? '');
        $tmpName = (string) ($fileInfo['tmp_name'] ?? '');

        if ($originalName === '' || $tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new RuntimeException('Tệp tải lên không hợp lệ.');
        }

        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new RuntimeException('Chỉ hỗ trợ ảnh JPG, JPEG, PNG, WEBP hoặc GIF.');
        }

        $storageDirectory = dirname(__DIR__, 2) . '/public/' . $relativeDirectory;
        if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0777, true) && !is_dir($storageDirectory)) {
            throw new RuntimeException('Không thể tạo thư mục lưu tệp tải lên.');
        }

        $safeBaseName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', pathinfo($originalName, PATHINFO_FILENAME));
        $safeBaseName = trim((string) $safeBaseName, '-');
        if ($safeBaseName === '') {
            $safeBaseName = 'roomverse';
        }

        $newFileName = $safeBaseName . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
        $targetPath = $storageDirectory . '/' . $newFileName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new RuntimeException('Không thể lưu tệp tải lên.');
        }

        return $relativeDirectory . '/' . $newFileName;
    }

    private function normalizeIdArray($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $normalized = array_map('intval', $value);

        return array_values(array_filter(
            $normalized,
            static fn(int $id): bool => $id > 0
        ));
    }

    private function toInt($value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (int) round((float) str_replace(',', '.', (string) $value));
    }

    private function toFloat($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) str_replace(',', '.', (string) $value);
    }

    private function nullIfEmpty($value): ?string
    {
        $trimmed = trim((string) $value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function fallbackText($value, string $fallback = 'Chưa có dữ liệu'): string
    {
        $trimmed = trim((string) $value);

        return $trimmed === '' ? $fallback : $trimmed;
    }
}
