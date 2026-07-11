<?php

declare(strict_types=1);

require_once __DIR__ . '/DoiTuongModel.php';
require_once __DIR__ . '/KhuVucModel.php';

/**
 * Repository dùng chung cho:
 * - Trang quản trị phòng trọ
 * - Trang chi tiết phòng
 * - Trang xem 360 VR
 *
 * Da sua:
 * - Tách riêng khỏi `PhongTroModel` để CRUD quản trị và trang chi tiết không
 *   làm phình logic tìm kiếm của trang chủ.
 * - Tái sử dụng các bảng hiện có để hạn chế thay đổi dữ liệu gốc.
 */
class RoomRepository
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAdminStats(): array
    {
        return [
            'total_rooms' => $this->countBySql('SELECT COUNT(*) FROM phong_tro'),
            'available_rooms' => $this->countBySql("SELECT COUNT(*) FROM phong_tro WHERE trang_thai = 'con_trong'"),
            'rented_rooms' => $this->countBySql("SELECT COUNT(*) FROM phong_tro WHERE trang_thai = 'da_thue'"),
            'vr_rooms' => $this->countBySql('SELECT COUNT(DISTINCT id_phong) FROM phong_vr_360'),
        ];
    }

    public function getAdminRooms(): array
    {
        $sql = "SELECT
                    p.id,
                    p.ten_phong,
                    p.so,
                    p.gia,
                    p.trang_thai,
                    p.created_at,
                    t.ten_dia_diem,
                    t.dia_chi,
                    (SELECT h.duong_dan FROM hinh_anh h
                        WHERE h.id_phong = p.id ORDER BY h.id ASC LIMIT 1) AS anh_dai_dien,
                    (SELECT COUNT(*) FROM hinh_anh h WHERE h.id_phong = p.id) AS so_anh,
                    (SELECT COUNT(*) FROM phong_vr_360 vr WHERE vr.id_phong = p.id) AS so_canh_vr
                FROM phong_tro p
                INNER JOIN toa_do t ON t.id = p.id_toa_do
                ORDER BY p.created_at DESC, p.id DESC";

        $result = $this->db->query($sql);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAdminFormOptions(): array
    {
        $doiTuongModel = new DoiTuongModel($this->db);
        $khuVucModel = new KhuVucModel($this->db);

        return [
            'doi_tuong_list' => $doiTuongModel->getAll(),
            'khu_vuc_list' => $khuVucModel->getAll(),
        ];
    }

    public function getRoomDetail(int $roomId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                p.id,
                p.id_toa_do,
                p.ten_phong,
                p.so,
                p.gia,
                p.dien_tich,
                p.tien_coc,
                p.so_nguoi_toi_da,
                p.trang_thai,
                p.gioi_tinh,
                p.mo_ta,
                p.created_at,
                p.id_doi_tuong,
                t.ten_dia_diem,
                t.dia_chi,
                t.latitude,
                t.longitude,
                t.link_google,
                dt.ten_doi_tuong,
                (SELECT ROUND(AVG(d.so_sao), 1) FROM danh_gia d
                    WHERE d.id_phong = p.id) AS diem_trung_binh,
                (SELECT COUNT(*) FROM danh_gia d
                    WHERE d.id_phong = p.id) AS so_luot_danh_gia
             FROM phong_tro p
             INNER JOIN toa_do t ON t.id = p.id_toa_do
             LEFT JOIN doi_tuong dt ON dt.id = p.id_doi_tuong
             WHERE p.id = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $room = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$room) {
            return null;
        }

        $room['images'] = $this->getRoomImages($roomId);
        $room['vr_scenes'] = $this->getRoomVrScenes($roomId);
        $room['amenities'] = $this->getRoomAmenities($roomId);
        $room['service_review'] = $this->getServiceReview($roomId);
        $room['nearby_sections'] = $this->getNearbySections();

        return $room;
    }

    public function createRoom(array $payload): int
    {
        $this->db->begin_transaction();

        try {
            $toaDoId = $this->insertCoordinate($payload);
            $roomId = $this->insertRoom($payload, $toaDoId);

            $this->saveAmenities($roomId, $payload['amenities']);
            $this->insertRoomImages($roomId, $payload['image_paths']);
            $this->saveServiceReview($roomId, $payload);
            $this->saveVrScenes($roomId, $payload['vr_scene_paths']);

            $this->db->commit();

            return $roomId;
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateRoom(int $roomId, array $payload): void
    {
        $this->updateRoomWithRelations($roomId, $payload);
    }

    public function updateRoomWithRelations(int $roomId, array $payload): void
    {
        $room = $this->getRoomDetail($roomId);

        if (!$room) {
            throw new RuntimeException('Không tìm thấy phòng trọ cần cập nhật.');
        }

        $this->db->begin_transaction();

        try {
            $this->updateCoordinateData((int) $room['id'], $payload);
            $this->updateRoomData($roomId, $payload);
            $this->saveAmenities($roomId, $payload['amenities']);
            $this->removeImages($roomId, $payload['remove_image_ids']);
            $this->removeVrScenes($roomId, $payload['remove_vr_ids']);
            $this->insertRoomImages($roomId, $payload['image_paths']);
            $this->saveServiceReview($roomId, $payload);
            $this->saveVrScenes($roomId, $payload['vr_scene_paths']);

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function deleteRoom(int $roomId): void
    {
        $stmt = $this->db->prepare('SELECT id_toa_do FROM phong_tro WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $room = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$room) {
            throw new RuntimeException('Không tìm thấy phòng trọ cần xóa.');
        }

        $toaDoId = (int) $room['id_toa_do'];

        $this->db->begin_transaction();

        try {
            $this->removeImages($roomId, $this->getImageIds($roomId));
            $this->removeVrScenes($roomId, $this->getVrIds($roomId));

            $stmt = $this->db->prepare('DELETE FROM tien_ich WHERE id_phong = ?');
            $stmt->bind_param('i', $roomId);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->db->prepare('DELETE FROM danh_gia WHERE id_phong = ?');
            $stmt->bind_param('i', $roomId);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->db->prepare('DELETE FROM living_simulation_room_reviews WHERE room_id = ?');
            $stmt->bind_param('i', $roomId);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->db->prepare('DELETE FROM phong_tro WHERE id = ?');
            $stmt->bind_param('i', $roomId);
            $stmt->execute();
            $stmt->close();

            $stmt = $this->db->prepare(
                'DELETE FROM toa_do
                 WHERE id = ? AND id_loai = 2
                 AND NOT EXISTS (SELECT 1 FROM phong_tro WHERE id_toa_do = ?)'
            );
            $stmt->bind_param('ii', $toaDoId, $toaDoId);
            $stmt->execute();
            $stmt->close();

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    private function insertCoordinate(array $payload): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO toa_do (ten_dia_diem, id_loai, latitude, longitude, link_google, dia_chi)
             VALUES (?, 2, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'sddss',
            $payload['location_name'],
            $payload['latitude'],
            $payload['longitude'],
            $payload['google_maps_url'],
            $payload['address']
        );
        $stmt->execute();
        $id = (int) $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    private function insertRoom(array $payload, int $toaDoId): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO phong_tro (
                ten_phong, so, gia, id_toa_do, dien_tich, tien_coc, so_nguoi_toi_da,
                trang_thai, id_doi_tuong, gioi_tinh, mo_ta, id_tai_khoan
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->bind_param(
            'ssdiddisiss',
            $payload['room_name'],
            $payload['room_code'],
            $payload['price'],
            $toaDoId,
            $payload['area'],
            $payload['deposit'],
            $payload['max_people'],
            $payload['status'],
            $payload['target_group_id'],
            $payload['gender'],
            $payload['description']
        );
        $stmt->execute();
        $id = (int) $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    private function updateCoordinateData(int $roomId, array $payload): void
    {
        $stmt = $this->db->prepare(
            'UPDATE toa_do t
             INNER JOIN phong_tro p ON p.id_toa_do = t.id
             SET t.ten_dia_diem = ?, t.latitude = ?, t.longitude = ?, t.link_google = ?, t.dia_chi = ?
             WHERE p.id = ?'
        );
        $stmt->bind_param(
            'sddssi',
            $payload['location_name'],
            $payload['latitude'],
            $payload['longitude'],
            $payload['google_maps_url'],
            $payload['address'],
            $roomId
        );
        $stmt->execute();
        $stmt->close();
    }

    private function updateRoomData(int $roomId, array $payload): void
    {
        $stmt = $this->db->prepare(
            "UPDATE phong_tro
             SET ten_phong = ?, so = ?, gia = ?, dien_tich = ?, tien_coc = ?,
                 so_nguoi_toi_da = ?, trang_thai = ?, id_doi_tuong = ?, gioi_tinh = ?, mo_ta = ?
             WHERE id = ?"
        );
        $stmt->bind_param(
            'ssdddisissi',
            $payload['room_name'],
            $payload['room_code'],
            $payload['price'],
            $payload['area'],
            $payload['deposit'],
            $payload['max_people'],
            $payload['status'],
            $payload['target_group_id'],
            $payload['gender'],
            $payload['description'],
            $roomId
        );
        $stmt->execute();
        $stmt->close();
    }

    private function saveAmenities(int $roomId, array $amenities): void
    {
        $existsStmt = $this->db->prepare('SELECT id_tien_ich FROM tien_ich WHERE id_phong = ? LIMIT 1');
        $existsStmt->bind_param('i', $roomId);
        $existsStmt->execute();
        $existing = $existsStmt->get_result()->fetch_assoc();
        $existsStmt->close();

        $wifi = $amenities['wifi'];
        $dieuHoa = $amenities['dieu_hoa'];
        $mayLanh = $amenities['may_lanh'];
        $mayGiat = $amenities['may_giat'];
        $maySay = $amenities['may_say'];
        $tuLanh = $amenities['tu_lanh'];
        $giuong = $amenities['giuong'];
        $tuQuanAo = $amenities['tu_quan_ao'];
        $banHoc = $amenities['ban_hoc'];
        $ghe = $amenities['ghe'];
        $remCua = $amenities['rem_cua'];
        $keBep = $amenities['ke_bep'];
        $mayNuocNong = $amenities['may_nuoc_nong'];

        if ($existing) {
            $stmt = $this->db->prepare(
                'UPDATE tien_ich
                 SET wifi = ?, dieu_hoa = ?, may_lanh = ?, may_giat = ?, may_say = ?, tu_lanh = ?,
                     giuong = ?, tu_quan_ao = ?, ban_hoc = ?, ghe = ?, rem_cua = ?, ke_bep = ?, may_nuoc_nong = ?
                 WHERE id_phong = ?'
            );
            $stmt->bind_param(
                'iiiiiiiiiiiiii',
                $wifi,
                $dieuHoa,
                $mayLanh,
                $mayGiat,
                $maySay,
                $tuLanh,
                $giuong,
                $tuQuanAo,
                $banHoc,
                $ghe,
                $remCua,
                $keBep,
                $mayNuocNong,
                $roomId
            );
            $stmt->execute();
            $stmt->close();
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO tien_ich (
                id_phong, wifi, dieu_hoa, may_lanh, may_giat, may_say, tu_lanh,
                giuong, tu_quan_ao, ban_hoc, ghe, rem_cua, ke_bep, may_nuoc_nong
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'iiiiiiiiiiiiii',
            $roomId,
            $wifi,
            $dieuHoa,
            $mayLanh,
            $mayGiat,
            $maySay,
            $tuLanh,
            $giuong,
            $tuQuanAo,
            $banHoc,
            $ghe,
            $remCua,
            $keBep,
            $mayNuocNong
        );
        $stmt->execute();
        $stmt->close();
    }

    private function saveServiceReview(int $roomId, array $payload): void
    {
        $existing = $this->getServiceReview($roomId);

        if ($existing) {
            $stmt = $this->db->prepare(
                'UPDATE living_simulation_room_reviews
                 SET room_name = ?, room_price = ?, electricity_price = ?, water_price = ?,
                     deposit_amount = ?, extra_utilities = ?, overall_review = ?
                 WHERE room_id = ?'
            );
            $stmt->bind_param(
                'siiiissi',
                $payload['room_name'],
                $payload['price'],
                $payload['electricity_price'],
                $payload['water_price'],
                $payload['deposit'],
                $payload['extra_utilities'],
                $payload['overall_review'],
                $roomId
            );
            $stmt->execute();
            $stmt->close();
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO living_simulation_room_reviews (
                room_id, room_name, room_price, electricity_price, water_price,
                deposit_amount, extra_utilities, overall_review
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'isiiiiss',
            $roomId,
            $payload['room_name'],
            $payload['price'],
            $payload['electricity_price'],
            $payload['water_price'],
            $payload['deposit'],
            $payload['extra_utilities'],
            $payload['overall_review']
        );
        $stmt->execute();
        $stmt->close();
    }

    private function saveVrScenes(int $roomId, array $vrScenePaths): void
    {
        foreach ($vrScenePaths as $sceneName => $scenePath) {
            if ($scenePath === '') {
                continue;
            }

            $existingStmt = $this->db->prepare(
                'SELECT id FROM phong_vr_360 WHERE id_phong = ? AND ten_goc_nhin = ? LIMIT 1'
            );
            $existingStmt->bind_param('is', $roomId, $sceneName);
            $existingStmt->execute();
            $existing = $existingStmt->get_result()->fetch_assoc();
            $existingStmt->close();

            if ($existing) {
                $stmt = $this->db->prepare(
                    'UPDATE phong_vr_360 SET duong_dan_anh = ? WHERE id = ?'
                );
                $vrId = (int) $existing['id'];
                $stmt->bind_param('si', $scenePath, $vrId);
                $stmt->execute();
                $stmt->close();
                continue;
            }

            $stmt = $this->db->prepare(
                'INSERT INTO phong_vr_360 (id_phong, ten_goc_nhin, duong_dan_anh)
                 VALUES (?, ?, ?)'
            );
            $stmt->bind_param('iss', $roomId, $sceneName, $scenePath);
            $stmt->execute();
            $stmt->close();
        }
    }

    private function insertRoomImages(int $roomId, array $imagePaths): void
    {
        foreach ($imagePaths as $imagePath) {
            if ($imagePath === '') {
                continue;
            }

            $stmt = $this->db->prepare(
                'INSERT INTO hinh_anh (id_phong, duong_dan) VALUES (?, ?)'
            );
            $stmt->bind_param('is', $roomId, $imagePath);
            $stmt->execute();
            $stmt->close();
        }
    }

    private function removeImages(int $roomId, array $imageIds): void
    {
        foreach ($imageIds as $imageId) {
            $stmt = $this->db->prepare('DELETE FROM hinh_anh WHERE id = ? AND id_phong = ?');
            $stmt->bind_param('ii', $imageId, $roomId);
            $stmt->execute();
            $stmt->close();
        }
    }

    private function removeVrScenes(int $roomId, array $vrIds): void
    {
        foreach ($vrIds as $vrId) {
            $stmt = $this->db->prepare('DELETE FROM phong_vr_360 WHERE id = ? AND id_phong = ?');
            $stmt->bind_param('ii', $vrId, $roomId);
            $stmt->execute();
            $stmt->close();
        }
    }

    private function getRoomImages(int $roomId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, duong_dan FROM hinh_anh WHERE id_phong = ? ORDER BY id ASC'
        );
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $images;
    }

    private function getRoomVrScenes(int $roomId): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, ten_goc_nhin, duong_dan_anh
             FROM phong_vr_360
             WHERE id_phong = ?
             ORDER BY FIELD(ten_goc_nhin, 'Phòng khách', 'Phòng bếp', 'Phòng ngủ'), id ASC"
        );
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $scenes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $scenes;
    }

    private function getRoomAmenities(int $roomId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tien_ich WHERE id_phong = ? LIMIT 1');
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    private function getServiceReview(int $roomId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, room_id, room_name, room_price, electricity_price, water_price,
                    deposit_amount, extra_utilities, overall_review
             FROM living_simulation_room_reviews
             WHERE room_id = ?
             ORDER BY id DESC
             LIMIT 1'
        );
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    private function getNearbySections(): array
    {
        return [
            [
                'title' => 'Trường học gần đó',
                'items' => [],
            ],
            [
                'title' => 'Chợ và tiện ích lân cận',
                'items' => [],
            ],
        ];
    }

    private function getImageIds(int $roomId): array
    {
        $rows = $this->getRoomImages($roomId);

        return array_map(static fn(array $row): int => (int) $row['id'], $rows);
    }

    private function getVrIds(int $roomId): array
    {
        $rows = $this->getRoomVrScenes($roomId);

        return array_map(static fn(array $row): int => (int) $row['id'], $rows);
    }

    private function countBySql(string $sql): int
    {
        $result = $this->db->query($sql);
        $row = $result ? $result->fetch_row() : null;

        return $row ? (int) $row[0] : 0;
    }
}
