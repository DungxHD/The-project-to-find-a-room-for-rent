<?php

declare(strict_types=1);

$currentPage = 'detail';
$room = $room ?? null;

function detailCurrency(float|int|string|null $value): string
{
    if ($value === null || $value === '') {
        return 'Chưa có dữ liệu';
    }

    return number_format((float) $value, 0, ',', '.') . ' đ';
}

function detailText(mixed $value, string $fallback = 'Chưa có dữ liệu'): string
{
    $text = trim((string) ($value ?? ''));

    return $text === '' ? $fallback : $text;
}

function detailAmenities(?array $amenities): array
{
    if (!$amenities) {
        return [];
    }

    $labels = [
        'wifi' => 'Wifi',
        'dieu_hoa' => 'Điều hòa',
        'may_lanh' => 'Quạt mát',
        'may_giat' => 'Máy giặt',
        'may_say' => 'Máy sấy',
        'tu_lanh' => 'Tủ lạnh',
        'giuong' => 'Giường',
        'tu_quan_ao' => 'Tủ quần áo',
        'ban_hoc' => 'Bàn học',
        'ghe' => 'Ghế',
        'rem_cua' => 'Rèm cửa',
        'ke_bep' => 'Kệ bếp',
        'may_nuoc_nong' => 'Máy nước nóng',
    ];

    $available = [];
    foreach ($labels as $key => $label) {
        if (!empty($amenities[$key])) {
            $available[] = $label;
        }
    }

    return $available;
}

function detailGenderLabel(?string $value): string
{
    return match ($value) {
        'nam' => 'Nam',
        'nu' => 'Nữ',
        'tat_ca' => 'Tất cả',
        default => 'Chưa có dữ liệu',
    };
}

$roomImages = $room['images'] ?? [];
$serviceReview = $room['service_review'] ?? null;
$availableAmenities = detailAmenities($room['amenities'] ?? null);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomVerse - Chi tiết phòng trọ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/portal.css">
</head>
<body class="page-portal page-detail">
    <?php include __DIR__ . '/../home/partials/header.php'; ?>

    <main class="portal-main">
        <?php if (!$room): ?>
            <section class="container portal-section">
                <div class="empty-state">
                    <h1>Không tìm thấy phòng trọ</h1>
                    <p>Phòng bạn đang truy cập chưa tồn tại hoặc đã bị xóa khỏi hệ thống.</p>
                    <div class="empty-actions">
                        <a class="btn btn-primary" href="index.php">Quay lại trang chủ</a>
                        <a class="btn btn-outline" href="index.php?page=admin">Mở trang quản trị</a>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <section class="portal-hero">
                <div class="container portal-hero-inner detail-hero">
                    <div>
                        <span class="portal-kicker">Chi tiết phòng trọ</span>
                        <h1><?= htmlspecialchars($room['ten_phong']) ?></h1>
                        <p><?= htmlspecialchars(detailText($room['dia_chi'] ?? $room['ten_dia_diem'])) ?></p>
                        <div class="detail-quick-meta">
                            <span><?= detailCurrency($room['gia']) ?>/tháng</span>
                            <span>Tiền cọc: <?= detailCurrency($room['tien_coc']) ?></span>
                            <span>Diện tích: <?= $room['dien_tich'] !== null ? htmlspecialchars((string) $room['dien_tich']) . ' m²' : 'Chưa có dữ liệu' ?></span>
                        </div>
                    </div>
                    <div class="portal-hero-actions">
                        <a class="btn btn-primary" href="index.php?page=vr&id=<?= (int) $room['id'] ?>">Xem 360 VR</a>
                        <a class="btn btn-outline" href="simulation.php?room_id=<?= (int) $room['id'] ?>">Sống thử AI</a>
                        <?php if (!empty($room['link_google'])): ?>
                            <a class="btn btn-outline" href="<?= htmlspecialchars($room['link_google']) ?>" target="_blank" rel="noopener">Mở Google Maps</a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="container portal-section detail-layout">
                <div class="detail-main">
                    <article class="detail-card">
                        <div class="section-heading">
                            <div>
                                <h2>Hình ảnh phòng trọ</h2>
                                <p><?= !empty($roomImages) ? 'Ảnh thực tế của phòng đang được hiển thị bên dưới.' : 'Chưa có dữ liệu ảnh cho phòng này.' ?></p>
                            </div>
                        </div>

                        <?php if (!empty($roomImages)): ?>
                            <div class="detail-gallery">
                                <div class="detail-gallery-main">
                                    <img src="<?= htmlspecialchars($roomImages[0]['duong_dan']) ?>" alt="<?= htmlspecialchars($room['ten_phong']) ?>">
                                </div>
                                <div class="detail-gallery-grid">
                                    <?php foreach ($roomImages as $image): ?>
                                        <img src="<?= htmlspecialchars($image['duong_dan']) ?>" alt="<?= htmlspecialchars($room['ten_phong']) ?>">
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="empty-inline">Chưa có dữ liệu ảnh phòng trọ.</div>
                        <?php endif; ?>
                    </article>

                    <article class="detail-card">
                        <div class="section-heading">
                            <div>
                                <h2>Mô tả chi tiết</h2>
                                <p>Thông tin tổng quan về phòng trọ và mức độ phù hợp.</p>
                            </div>
                        </div>

                        <div class="detail-info-grid">
                            <div class="info-item">
                                <span class="info-label">Mã phòng</span>
                                <strong><?= htmlspecialchars(detailText($room['so'])) ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Đối tượng phù hợp</span>
                                <strong><?= htmlspecialchars(detailText($room['ten_doi_tuong'])) ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Giới tính phù hợp</span>
                                <strong><?= htmlspecialchars(detailGenderLabel($room['gioi_tinh'] ?? null)) ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Số người tối đa</span>
                                <strong><?= $room['so_nguoi_toi_da'] !== null ? (int) $room['so_nguoi_toi_da'] . ' người' : 'Chưa có dữ liệu' ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Đánh giá trung bình</span>
                                <strong>
                                    <?= $room['diem_trung_binh'] !== null
                                        ? htmlspecialchars((string) $room['diem_trung_binh']) . ' / 5 (' . (int) ($room['so_luot_danh_gia'] ?? 0) . ' lượt)'
                                        : 'Chưa có dữ liệu' ?>
                                </strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Trạng thái</span>
                                <strong><?= $room['trang_thai'] === 'da_thue' ? 'Đã thuê' : 'Còn trống' ?></strong>
                            </div>
                        </div>

                        <p class="detail-description"><?= nl2br(htmlspecialchars(detailText($room['mo_ta']))) ?></p>
                    </article>

                    <article class="detail-card">
                        <div class="section-heading">
                            <div>
                                <h2>Giá dịch vụ và nhận xét</h2>
                                <p>Thông tin chi phí phục vụ cho quyết định thuê và trải nghiệm sống thử.</p>
                            </div>
                        </div>

                        <div class="detail-info-grid">
                            <div class="info-item">
                                <span class="info-label">Giá phòng</span>
                                <strong><?= detailCurrency($serviceReview['room_price'] ?? $room['gia'] ?? null) ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Giá điện</span>
                                <strong><?= detailCurrency($serviceReview['electricity_price'] ?? null) ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Giá nước</span>
                                <strong><?= detailCurrency($serviceReview['water_price'] ?? null) ?></strong>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tiền cọc</span>
                                <strong><?= detailCurrency($serviceReview['deposit_amount'] ?? $room['tien_coc'] ?? null) ?></strong>
                            </div>
                        </div>

                        <div class="detail-note-block">
                            <h3>Dịch vụ đi kèm</h3>
                            <p><?= htmlspecialchars(detailText($serviceReview['extra_utilities'] ?? null, 'Không có')) ?></p>
                        </div>

                        <div class="detail-note-block">
                            <h3>Nhận xét tổng quan</h3>
                            <p><?= htmlspecialchars(detailText($serviceReview['overall_review'] ?? null)) ?></p>
                        </div>
                    </article>
                </div>

                <aside class="detail-sidebar">
                    <article class="detail-card">
                        <div class="section-heading">
                            <div>
                                <h2>Tiện ích trong phòng</h2>
                                <p>Danh sách tiện ích hiện có.</p>
                            </div>
                        </div>

                        <?php if (!empty($availableAmenities)): ?>
                            <ul class="tag-list">
                                <?php foreach ($availableAmenities as $amenityLabel): ?>
                                    <li><?= htmlspecialchars($amenityLabel) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="empty-inline">Chưa có dữ liệu tiện ích.</div>
                        <?php endif; ?>
                    </article>

                    <article class="detail-card">
                        <div class="section-heading">
                            <div>
                                <h2>Khoảng cách lân cận</h2>
                                <p>Các địa điểm gần phòng trọ để người dùng tham khảo.</p>
                            </div>
                        </div>

                        <?php foreach (($room['nearby_sections'] ?? []) as $section): ?>
                            <div class="detail-nearby-group">
                                <h3><?= htmlspecialchars($section['title']) ?></h3>
                                <?php if (!empty($section['items'])): ?>
                                    <ul class="nearby-list">
                                        <?php foreach ($section['items'] as $item): ?>
                                            <li><?= htmlspecialchars((string) $item) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <div class="empty-inline">Chưa có dữ liệu.</div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </article>

                    <article class="detail-card">
                        <div class="section-heading">
                            <div>
                                <h2>Trải nghiệm nhanh</h2>
                                <p>Mở ngay không gian 360 hoặc mô phỏng sống thử.</p>
                            </div>
                        </div>

                        <div class="stack-actions">
                            <a class="btn btn-primary" href="index.php?page=vr&id=<?= (int) $room['id'] ?>">Xem 360 VR</a>
                            <a class="btn btn-outline" href="simulation.php?room_id=<?= (int) $room['id'] ?>">Sống thử AI</a>
                            <a class="btn btn-outline" href="index.php?page=admin&edit=<?= (int) $room['id'] ?>">Sửa trong admin</a>
                        </div>
                    </article>
                </aside>
            </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../home/partials/footer.php'; ?>
</body>
</html>
