<?php

declare(strict_types=1);

/**
 * Model tìm kiếm phòng trọ.
 *
 * Da giu logic goc:
 * - Có điểm tham chiếu thì tính Haversine và sắp xếp gần nhất.
 * - Không có điểm tham chiếu thì ưu tiên phòng mới nhất.
 * - Giới hạn mặc định 4 kết quả ở trang chủ.
 */
class PhongTroModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * @param array{
     *   doi_tuong_id?: int|null,
     *   gia_min?: int|null,
     *   gia_max?: int|null,
     *   center_lat?: float|null,
     *   center_lng?: float|null,
     *   khoang_cach_km?: float|null,
     *   limit?: int
     * } $filters
     */
    public function search(array $filters): array
    {
        $doiTuongId = $filters['doi_tuong_id'] ?? null;
        $giaMin = $filters['gia_min'] ?? null;
        $giaMax = $filters['gia_max'] ?? null;
        $centerLat = $filters['center_lat'] ?? null;
        $centerLng = $filters['center_lng'] ?? null;
        $khoangCachKm = $filters['khoang_cach_km'] ?? null;
        $limit = (int) ($filters['limit'] ?? 4);

        $hasCenter = ($centerLat !== null && $centerLng !== null);

        $distanceExpr = $hasCenter
            ? '(6371 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(t.latitude)) *
                    COS(RADIANS(t.longitude) - RADIANS(?)) +
                    SIN(RADIANS(?)) * SIN(RADIANS(t.latitude))
                ))'
            : 'NULL';

        $sql = "SELECT
                    p.id, p.ten_phong, p.so, p.gia, p.dien_tich, p.tien_coc,
                    p.so_nguoi_toi_da, p.trang_thai, p.gioi_tinh, p.mo_ta,
                    p.created_at,
                    t.ten_dia_diem, t.dia_chi, t.link_google,
                    (SELECT h.duong_dan FROM hinh_anh h
                        WHERE h.id_phong = p.id ORDER BY h.id ASC LIMIT 1) AS anh_dai_dien,
                    (SELECT ROUND(AVG(d.so_sao), 1) FROM danh_gia d
                        WHERE d.id_phong = p.id) AS diem_trung_binh,
                    (SELECT COUNT(*) FROM danh_gia d
                        WHERE d.id_phong = p.id) AS so_luot_danh_gia,
                    $distanceExpr AS khoang_cach
                FROM phong_tro p
                INNER JOIN toa_do t ON t.id = p.id_toa_do
                WHERE p.trang_thai = 'con_trong'";

        $types = '';
        $params = [];

        if ($hasCenter) {
            $types .= 'ddd';
            $params[] = $centerLat;
            $params[] = $centerLng;
            $params[] = $centerLat;
        }

        if ($doiTuongId !== null) {
            $sql .= ' AND p.id_doi_tuong = ?';
            $types .= 'i';
            $params[] = $doiTuongId;
        }

        if ($giaMin !== null) {
            $sql .= ' AND p.gia >= ?';
            $types .= 'i';
            $params[] = $giaMin;
        }

        if ($giaMax !== null) {
            $sql .= ' AND p.gia <= ?';
            $types .= 'i';
            $params[] = $giaMax;
        }

        if ($hasCenter && $khoangCachKm !== null) {
            $sql .= ' HAVING khoang_cach <= ?';
            $types .= 'd';
            $params[] = $khoangCachKm;
        }

        $sql .= $hasCenter
            ? ' ORDER BY khoang_cach ASC, p.created_at DESC'
            : ' ORDER BY p.created_at DESC';

        $sql .= ' LIMIT ?';
        $types .= 'i';
        $params[] = $limit;

        $stmt = $this->db->prepare($sql);

        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $this->attachAmenities($rows);
    }

    /**
     * Lấy chi tiết 1 phòng theo id để nối sang module sống thử.
     *
     * Da sua:
     * - Bỏ bước gọi `search(['limit' => 1])` vì có thể trả sai phòng và tốn
     *   thêm 1 truy vấn không cần thiết.
     * - Dùng truy vấn trực tiếp theo `p.id` nhưng vẫn giữ nguyên dữ liệu trả về
     *   để không làm đổi giao diện hiện tại.
     */
    public function findById(int $roomId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT
                p.id, p.ten_phong, p.so, p.gia, p.dien_tich, p.tien_coc,
                p.so_nguoi_toi_da, p.trang_thai, p.gioi_tinh, p.mo_ta,
                p.created_at, t.ten_dia_diem, t.dia_chi, t.link_google,
                (SELECT h.duong_dan FROM hinh_anh h
                    WHERE h.id_phong = p.id ORDER BY h.id ASC LIMIT 1) AS anh_dai_dien,
                (SELECT ROUND(AVG(d.so_sao), 1) FROM danh_gia d
                    WHERE d.id_phong = p.id) AS diem_trung_binh,
                (SELECT COUNT(*) FROM danh_gia d
                    WHERE d.id_phong = p.id) AS so_luot_danh_gia,
                NULL AS khoang_cach
             FROM phong_tro p
             INNER JOIN toa_do t ON t.id = p.id_toa_do
             WHERE p.id = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) {
            return null;
        }

        return $this->attachAmenities([$row])[0];
    }

    private function attachAmenities(array $rows): array
    {
        foreach ($rows as &$row) {
            $row['tien_ich'] = $this->getTienIch((int) $row['id']);
        }
        unset($row);

        return $rows;
    }

    private function getTienIch(int $idPhong): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tien_ich WHERE id_phong = ? LIMIT 1');
        $stmt->bind_param('i', $idPhong);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }
}
