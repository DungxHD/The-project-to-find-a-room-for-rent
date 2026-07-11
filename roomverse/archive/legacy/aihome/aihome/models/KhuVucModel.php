<?php

declare(strict_types=1);

/**
 * "Khu vực" trong ô tìm kiếm dùng chính bảng toa_do, lọc theo
 * id_loai = 1 (xem ghi chú trong database/schema.sql).
 */
class KhuVucModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, ten_dia_diem, latitude, longitude
                FROM toa_do
                WHERE id_loai = 1
                ORDER BY ten_dia_diem ASC';
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, ten_dia_diem, latitude, longitude FROM toa_do WHERE id = ? AND id_loai = 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}