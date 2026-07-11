<?php

declare(strict_types=1);

class DoiTuongModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /** Lấy toàn bộ đối tượng thuê trọ (Sinh viên / Người đi làm...) */
    public function getAll(): array
    {
        $result = $this->db->query('SELECT id, ten_doi_tuong FROM doi_tuong ORDER BY id ASC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}