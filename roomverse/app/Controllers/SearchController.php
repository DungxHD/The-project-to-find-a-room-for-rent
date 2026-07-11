<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/KhuVucModel.php';
require_once __DIR__ . '/../Models/PhongTroModel.php';

class SearchController
{
    private PhongTroModel $phongTroModel;
    private KhuVucModel $khuVucModel;

    public function __construct(mysqli $db)
    {
        $this->phongTroModel = new PhongTroModel($db);
        $this->khuVucModel = new KhuVucModel($db);
    }

    /**
     * Nhận input từ form/AJAX, làm sạch và trả về danh sách phòng trọ.
     */
    public function handle(array $input): array
    {
        $doiTuongId = isset($input['doi_tuong_id']) && $input['doi_tuong_id'] !== ''
            ? (int) $input['doi_tuong_id']
            : null;

        $giaMin = isset($input['gia_min']) && $input['gia_min'] !== ''
            ? (int) $input['gia_min']
            : null;

        $giaMax = isset($input['gia_max']) && $input['gia_max'] !== ''
            ? (int) $input['gia_max']
            : null;

        $khoangCachKm = isset($input['khoang_cach_km']) && $input['khoang_cach_km'] !== ''
            ? (float) $input['khoang_cach_km']
            : null;

        $centerLat = null;
        $centerLng = null;

        if (!empty($input['user_lat']) && !empty($input['user_lng'])) {
            $centerLat = (float) $input['user_lat'];
            $centerLng = (float) $input['user_lng'];
        } elseif (!empty($input['khu_vuc_id'])) {
            $khuVuc = $this->khuVucModel->find((int) $input['khu_vuc_id']);

            if ($khuVuc) {
                $centerLat = (float) $khuVuc['latitude'];
                $centerLng = (float) $khuVuc['longitude'];
            }
        }

        $rooms = $this->phongTroModel->search([
            'doi_tuong_id' => $doiTuongId,
            'gia_min' => $giaMin,
            'gia_max' => $giaMax,
            'center_lat' => $centerLat,
            'center_lng' => $centerLng,
            'khoang_cach_km' => $khoangCachKm,
            'limit' => 4,
        ]);

        return [
            'success' => true,
            'count' => count($rooms),
            'data' => $rooms,
        ];
    }
}
