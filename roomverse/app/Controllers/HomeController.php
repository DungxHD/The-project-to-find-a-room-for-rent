<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/DoiTuongModel.php';
require_once __DIR__ . '/../Models/KhuVucModel.php';
require_once __DIR__ . '/../Models/PhongTroModel.php';

class HomeController
{
    private DoiTuongModel $doiTuongModel;
    private KhuVucModel $khuVucModel;
    private PhongTroModel $phongTroModel;

    public function __construct(mysqli $db)
    {
        $this->doiTuongModel = new DoiTuongModel($db);
        $this->khuVucModel = new KhuVucModel($db);
        $this->phongTroModel = new PhongTroModel($db);
    }

    public function getHomePageData(): array
    {
        return [
            'doiTuongList' => $this->doiTuongModel->getAll(),
            'khuVucList' => $this->khuVucModel->getAll(),
            'rooms' => $this->phongTroModel->search(['limit' => 4]),
            'heroImages' => [
                'assets/images/hero-banner-1.png',
                'assets/images/hero-banner-2.png',
            ],
        ];
    }
}
