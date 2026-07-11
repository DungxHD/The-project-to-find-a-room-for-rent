<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomVerse - Tìm phòng trọ thông minh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/portal.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <?php include __DIR__ . '/partials/hero_banner.php'; ?>

        <section class="search-panel container" id="search_1">
            <form id="searchForm" class="search-form">
                <div class="search-field">
                    <label for="doiTuong">Tìm cho</label>
                    <select id="doiTuong" name="doi_tuong_id">
                        <option value="">Tất cả</option>
                        <?php foreach ($doiTuongList as $dt): ?>
                            <option value="<?= (int) $dt['id'] ?>"><?= htmlspecialchars($dt['ten_doi_tuong']) ?></option>
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
                            <option value="<?= (int) $kv['id'] ?>"><?= htmlspecialchars($kv['ten_dia_diem']) ?></option>
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

                <button type="submit" class="btn btn-primary btn-search">Tìm kiếm</button>
            </form>
            <p class="search-hint">
                Mẹo: bấm <button type="button" id="useMyLocationBtn" class="link-btn">dùng vị trí của tôi</button>
                để tính khoảng cách chính xác hơn thay vì theo khu vực đã chọn.
            </p>
        </section>

        <section class="results-section container">
            <div class="results-header">
                <h2>Phòng trọ mới nhất</h2>
                <a href="index.php?page=admin" class="see-all">Quản lý phòng trọ</a>
            </div>

            <div id="roomGrid" class="room-grid">
                <?php foreach ($rooms as $room): ?>
                    <?php include __DIR__ . '/partials/room_card.php'; ?>
                <?php endforeach; ?>
                <?php if (empty($rooms)): ?>
                    <p class="no-results">Không tìm thấy phòng trọ phù hợp.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="features-section" id="product-value">
            <div class="container features-grid">
                <div class="feature-item">
                    <span class="feature-icon">AI</span>
                    <div>
                        <h4>AI gợi ý thông minh</h4>
                        <p>Đề xuất phòng phù hợp dựa trên nhu cầu, ngân sách và vị trí di chuyển.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">VR</span>
                    <div>
                        <h4>Tham quan phòng trực tuyến</h4>
                        <p>Xem không gian phòng trọ theo góc nhìn 360 độ và chuyển cảnh giữa các khu vực.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">GPS</span>
                    <div>
                        <h4>Định vị thông minh</h4>
                        <p>Tìm phòng trong phạm vi phù hợp với phương tiện và khu vực sinh hoạt thực tế.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">SIM</span>
                    <div>
                        <h4>Sống thử trước khi thuê</h4>
                        <p>Chuyển thẳng sang mô phỏng sinh hoạt để đánh giá trải nghiệm trước khi quyết định thuê.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="assets/js/home.js"></script>
</body>
</html>
