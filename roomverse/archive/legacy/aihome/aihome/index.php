<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/DoiTuongModel.php';
require_once __DIR__ . '/models/KhuVucModel.php';
require_once __DIR__ . '/controllers/SearchController.php';

$db = getDbConnection();

$doiTuongModel = new DoiTuongModel($db);
$khuVucModel   = new KhuVucModel($db);
$controller    = new SearchController($db);

$doiTuongList = $doiTuongModel->getAll();
$khuVucList   = $khuVucModel->getAll();

// Kết quả mặc định khi tải trang lần đầu: 4 phòng mới nhất (chưa lọc theo khoảng cách)
$initialResult = $controller->handle([]);
$rooms = $initialResult['data'];

// Hero banner: chỉ lưu ảnh, đổi đường dẫn tại đây khi có ảnh thật
$heroImages = [
    'assets/images/hero-banner-1.png',
    'assets/images/hero-banner-2.png',
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIHome - Tìm phòng trọ thông minh bằng AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include __DIR__ . '/views/partials/header.php'; ?>

    <main>
        <?php include __DIR__ . '/views/partials/hero_banner.php'; ?>

        <section class="search-panel container" id='search_1'>
            <form id="searchForm" class="search-form">
                <div class="search-field">
                    <label for="doiTuong">Tìm cho</label>
                    <select id="doiTuong" name="doi_tuong_id">
                        <option value="">Tất cả</option>
                        <?php foreach ($doiTuongList as $dt): ?>
                        <option value="<?= (int)$dt['id'] ?>"><?= htmlspecialchars($dt['ten_doi_tuong']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="search-field">
                    <label for="giaKhoang">Khoảng giá</label>
                    <select id="giaKhoang" name="gia_khoang">
                        <option value="">Tất cả</option>
                        <option value="0-1000000">Dưới 1.000.000 đ</option>
                        <option value="1000000-3000000" selected>1.000.000 - 3.000.000 đ</option>
                        <option value="3000000-5000000">3.000.000 - 5.000.000 đ</option>
                        <option value="5000000-999999999">Trên 5.000.000 đ</option>
                    </select>
                </div>

                <div class="search-field">
                    <label for="khuVuc">Khu vực</label>
                    <select id="khuVuc" name="khu_vuc_id">
                        <option value="">Tất cả khu vực</option>
                        <?php foreach ($khuVucList as $kv): ?>
                        <option value="<?= (int)$kv['id'] ?>"><?= htmlspecialchars($kv['ten_dia_diem']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="search-field">
                    <label for="khoangCach">Khoảng cách</label>
                    <select id="khoangCach" name="khoang_cach_km">
                        <option value="">Không giới hạn</option>
                        <option value="1">Dưới 1 km</option>
                        <option value="3" selected>Dưới 3 km</option>
                        <option value="5">Dưới 5 km</option>
                        <option value="10">Dưới 10 km</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-search">🔍 Tìm kiếm</button>
            </form>
            <p class="search-hint">
                Mẹo: bấm <button type="button" id="useMyLocationBtn" class="link-btn">dùng vị trí của tôi</button>
                để tính khoảng cách chính xác hơn thay vì theo khu vực đã chọn.
            </p>
        </section>

        <section class="results-section container">
            <div class="results-header">
                <h2>Phòng trọ mới nhất ✨</h2>
                <a href="#" class="see-all">Xem tất cả →</a>
            </div>

            <div id="roomGrid" class="room-grid">
                <?php foreach ($rooms as $room): ?>
                <?php include __DIR__ . '/views/partials/room_card.php'; ?>
                <?php endforeach; ?>
                <?php if (empty($rooms)): ?>
                <p class="no-results">Không tìm thấy phòng trọ phù hợp.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="features-section">
            <div class="container features-grid">
                <div class="feature-item">
                    <span class="feature-icon">✨</span>
                    <div>
                        <h4>AI gợi ý thông minh</h4>
                        <p>Đề xuất phòng trọ phù hợp nhất với nhu cầu của bạn.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⏱</span>
                    <div>
                        <h4>Tiết kiệm thời gian</h4>
                        <p>Tìm kiếm nhanh chóng, lọc kết quả chỉ trong vài giây.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🛡</span>
                    <div>
                        <h4>Thông tin minh bạch</h4>
                        <p>Hình ảnh thật, giá rõ ràng, đánh giá chân thực.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🕶</span>
                    <div>
                        <h4>Trải nghiệm thực tế ảo</h4>
                        <p>Xem phòng 360° sống động như đang ở trực tiếp.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/views/partials/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>

</html>