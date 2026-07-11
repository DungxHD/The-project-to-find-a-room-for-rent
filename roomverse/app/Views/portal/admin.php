<?php

declare(strict_types=1);

$currentPage = 'admin';
$editingRoom = $editingRoom ?? null;
$stats = $stats ?? [];
$adminRooms = $adminRooms ?? [];
$formOptions = $formOptions ?? ['doi_tuong_list' => [], 'khu_vuc_list' => []];
$flashMessage = $flashMessage ?? '';
$errorMessage = $errorMessage ?? '';
$isEditMode = $editingRoom !== null;
$roomAmenities = $editingRoom['amenities'] ?? [];
$serviceReview = $editingRoom['service_review'] ?? [];
$existingImages = $editingRoom['images'] ?? [];
$existingVrScenes = $editingRoom['vr_scenes'] ?? [];

function adminValue(?array $source, string $key, string $fallback = ''): string
{
    if (!$source || !array_key_exists($key, $source) || $source[$key] === null) {
        return $fallback;
    }

    return (string) $source[$key];
}

function adminChecked(array $source, string $key): string
{
    return !empty($source[$key]) ? 'checked' : '';
}

function adminSelected(string $currentValue, string $expectedValue): string
{
    return $currentValue === $expectedValue ? 'selected' : '';
}

function adminCurrency(float|int|string|null $value): string
{
    if ($value === null || $value === '') {
        return '0';
    }

    return number_format((float) $value, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomVerse - Quản trị phòng trọ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/portal.css">
</head>
<body class="page-portal page-admin">
    <?php include __DIR__ . '/../home/partials/header.php'; ?>

    <main class="portal-main">
        <section class="portal-hero">
            <div class="container portal-hero-inner">
                <div>
                    <span class="portal-kicker">Quản trị RoomVerse</span>
                    <h1>Thêm, sửa, xóa và thống kê phòng trọ</h1>
                    <p>Truy cập qua URL `index.php?page=admin` hoặc `index.php?action=admin` để quản lý dữ liệu phòng trọ, ảnh thường và ảnh 360 VR.</p>
                </div>
                <div class="portal-hero-actions">
                    <a class="btn btn-primary" href="index.php">Về trang chủ</a>
                    <a class="btn btn-outline" href="index.php?page=admin">Làm mới trang quản trị</a>
                </div>
            </div>
        </section>

        <section class="container portal-section">
            <?php if ($flashMessage !== ''): ?>
                <div class="portal-alert portal-alert-success"><?= htmlspecialchars($flashMessage) ?></div>
            <?php endif; ?>

            <?php if ($errorMessage !== ''): ?>
                <div class="portal-alert portal-alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <div class="stats-grid">
                <article class="stat-card">
                    <span class="stat-label">Tổng số phòng</span>
                    <strong class="stat-value"><?= (int) ($stats['total_rooms'] ?? 0) ?></strong>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Phòng còn trống</span>
                    <strong class="stat-value"><?= (int) ($stats['available_rooms'] ?? 0) ?></strong>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Phòng đã thuê</span>
                    <strong class="stat-value"><?= (int) ($stats['rented_rooms'] ?? 0) ?></strong>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Phòng có 360 VR</span>
                    <strong class="stat-value"><?= (int) ($stats['vr_rooms'] ?? 0) ?></strong>
                </article>
            </div>
        </section>

        <section class="container portal-section admin-layout">
            <div class="admin-form-panel">
                <div class="section-heading">
                    <div>
                        <h2><?= $isEditMode ? 'Cập nhật phòng trọ' : 'Thêm phòng trọ mới' ?></h2>
                        <p><?= $isEditMode ? 'Chỉnh sửa trực tiếp thông tin phòng, ảnh và dữ liệu VR.' : 'Nhập thông tin cơ bản, giá dịch vụ, tiện ích và ảnh để tạo phòng mới.' ?></p>
                    </div>
                    <?php if ($isEditMode): ?>
                        <a class="btn btn-outline btn-sm" href="index.php?page=admin">Tạo bản ghi mới</a>
                    <?php endif; ?>
                </div>

                <form class="admin-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="form_action" value="<?= $isEditMode ? 'update_room' : 'create_room' ?>">
                    <?php if ($isEditMode): ?>
                        <input type="hidden" name="room_id" value="<?= (int) $editingRoom['id'] ?>">
                    <?php endif; ?>

                    <div class="form-grid form-grid-2">
                        <label class="form-field">
                            <span>Tên phòng</span>
                            <input type="text" name="room_name" required value="<?= htmlspecialchars(adminValue($editingRoom, 'ten_phong')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Mã phòng</span>
                            <input type="text" name="room_code" required value="<?= htmlspecialchars(adminValue($editingRoom, 'so')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Giá thuê / tháng</span>
                            <input type="number" name="price" min="0" step="1000" value="<?= htmlspecialchars(adminValue($serviceReview, 'room_price', adminValue($editingRoom, 'gia', '0'))) ?>">
                        </label>
                        <label class="form-field">
                            <span>Tiền cọc</span>
                            <input type="number" name="deposit" min="0" step="1000" value="<?= htmlspecialchars(adminValue($serviceReview, 'deposit_amount', adminValue($editingRoom, 'tien_coc', '0'))) ?>">
                        </label>
                        <label class="form-field">
                            <span>Giá điện</span>
                            <input type="number" name="electricity_price" min="0" step="100" value="<?= htmlspecialchars(adminValue($serviceReview, 'electricity_price', '0')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Giá nước</span>
                            <input type="number" name="water_price" min="0" step="100" value="<?= htmlspecialchars(adminValue($serviceReview, 'water_price', '0')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Diện tích (m²)</span>
                            <input type="number" name="area" min="0" step="0.1" value="<?= htmlspecialchars(adminValue($editingRoom, 'dien_tich', '0')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Số người tối đa</span>
                            <input type="number" name="max_people" min="1" step="1" value="<?= htmlspecialchars(adminValue($editingRoom, 'so_nguoi_toi_da', '1')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Trạng thái</span>
                            <select name="status">
                                <?php $statusValue = adminValue($editingRoom, 'trang_thai', 'con_trong'); ?>
                                <option value="con_trong" <?= adminSelected($statusValue, 'con_trong') ?>>Còn trống</option>
                                <option value="da_thue" <?= adminSelected($statusValue, 'da_thue') ?>>Đã thuê</option>
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Đối tượng</span>
                            <?php $targetGroupValue = adminValue($editingRoom, 'id_doi_tuong'); ?>
                            <select name="target_group_id" required>
                                <option value="">Chọn đối tượng</option>
                                <?php foreach ($formOptions['doi_tuong_list'] as $doiTuong): ?>
                                    <option
                                        value="<?= (int) $doiTuong['id'] ?>"
                                        <?= adminSelected($targetGroupValue, (string) $doiTuong['id']) ?>
                                    >
                                        <?= htmlspecialchars($doiTuong['ten_doi_tuong']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Giới tính phù hợp</span>
                            <?php $genderValue = adminValue($editingRoom, 'gioi_tinh', 'tat_ca'); ?>
                            <select name="gender">
                                <option value="tat_ca" <?= adminSelected($genderValue, 'tat_ca') ?>>Tất cả</option>
                                <option value="nam" <?= adminSelected($genderValue, 'nam') ?>>Nam</option>
                                <option value="nu" <?= adminSelected($genderValue, 'nu') ?>>Nữ</option>
                            </select>
                        </label>
                        <label class="form-field">
                            <span>Tên vị trí</span>
                            <input type="text" name="location_name" value="<?= htmlspecialchars(adminValue($editingRoom, 'ten_dia_diem', 'Chưa có dữ liệu')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Địa chỉ</span>
                            <input type="text" name="address" value="<?= htmlspecialchars(adminValue($editingRoom, 'dia_chi', 'Chưa có dữ liệu')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Vĩ độ</span>
                            <input type="number" name="latitude" step="0.0000001" value="<?= htmlspecialchars(adminValue($editingRoom, 'latitude', '0')) ?>">
                        </label>
                        <label class="form-field">
                            <span>Kinh độ</span>
                            <input type="number" name="longitude" step="0.0000001" value="<?= htmlspecialchars(adminValue($editingRoom, 'longitude', '0')) ?>">
                        </label>
                        <label class="form-field form-field-full">
                            <span>Link Google Maps</span>
                            <input type="url" name="google_maps_url" value="<?= htmlspecialchars(adminValue($editingRoom, 'link_google')) ?>">
                        </label>
                        <label class="form-field form-field-full">
                            <span>Mô tả phòng</span>
                            <textarea name="description" rows="4"><?= htmlspecialchars(adminValue($editingRoom, 'mo_ta', 'Chưa có dữ liệu')) ?></textarea>
                        </label>
                        <label class="form-field form-field-full">
                            <span>Dịch vụ / tiện ích tính thêm</span>
                            <textarea name="extra_utilities" rows="3"><?= htmlspecialchars(adminValue($serviceReview, 'extra_utilities', 'Không có')) ?></textarea>
                        </label>
                        <label class="form-field form-field-full">
                            <span>Đánh giá tổng quan</span>
                            <textarea name="overall_review" rows="3"><?= htmlspecialchars(adminValue($serviceReview, 'overall_review', 'Chưa có dữ liệu')) ?></textarea>
                        </label>
                    </div>

                    <div class="form-block">
                        <h3>Tiện ích có sẵn</h3>
                        <div class="checkbox-grid">
                            <label><input type="checkbox" name="amenities[wifi]" <?= adminChecked($roomAmenities, 'wifi') ?>> Wifi</label>
                            <label><input type="checkbox" name="amenities[dieu_hoa]" <?= adminChecked($roomAmenities, 'dieu_hoa') ?>> Điều hòa</label>
                            <label><input type="checkbox" name="amenities[may_lanh]" <?= adminChecked($roomAmenities, 'may_lanh') ?>> Quạt mát</label>
                            <label><input type="checkbox" name="amenities[may_giat]" <?= adminChecked($roomAmenities, 'may_giat') ?>> Máy giặt</label>
                            <label><input type="checkbox" name="amenities[may_say]" <?= adminChecked($roomAmenities, 'may_say') ?>> Máy sấy</label>
                            <label><input type="checkbox" name="amenities[tu_lanh]" <?= adminChecked($roomAmenities, 'tu_lanh') ?>> Tủ lạnh</label>
                            <label><input type="checkbox" name="amenities[giuong]" <?= adminChecked($roomAmenities, 'giuong') ?>> Giường</label>
                            <label><input type="checkbox" name="amenities[tu_quan_ao]" <?= adminChecked($roomAmenities, 'tu_quan_ao') ?>> Tủ quần áo</label>
                            <label><input type="checkbox" name="amenities[ban_hoc]" <?= adminChecked($roomAmenities, 'ban_hoc') ?>> Bàn học</label>
                            <label><input type="checkbox" name="amenities[ghe]" <?= adminChecked($roomAmenities, 'ghe') ?>> Ghế</label>
                            <label><input type="checkbox" name="amenities[rem_cua]" <?= adminChecked($roomAmenities, 'rem_cua') ?>> Rèm cửa</label>
                            <label><input type="checkbox" name="amenities[ke_bep]" <?= adminChecked($roomAmenities, 'ke_bep') ?>> Kệ bếp</label>
                            <label><input type="checkbox" name="amenities[may_nuoc_nong]" <?= adminChecked($roomAmenities, 'may_nuoc_nong') ?>> Máy nước nóng</label>
                        </div>
                    </div>

                    <div class="form-block">
                        <h3>Ảnh phòng trọ</h3>
                        <p class="form-note">Có thể tải lên nhiều ảnh cùng lúc. Nếu chưa có dữ liệu, phần người dùng sẽ hiển thị “Chưa có dữ liệu”.</p>
                        <input type="file" name="room_images[]" accept="image/*" multiple>

                        <?php if (!empty($existingImages)): ?>
                            <div class="media-grid">
                                <?php foreach ($existingImages as $image): ?>
                                    <label class="media-card">
                                        <img src="<?= htmlspecialchars($image['duong_dan']) ?>" alt="Ảnh phòng hiện tại">
                                        <span>Gỡ ảnh hiện tại</span>
                                        <input type="checkbox" name="remove_image_ids[]" value="<?= (int) $image['id'] ?>">
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-block">
                        <h3>Ảnh 360 VR</h3>
                        <p class="form-note">Tải lên ảnh panorama cho từng khu vực để người dùng có thể xoay 360 và xem toàn màn hình.</p>

                        <div class="form-grid form-grid-3">
                            <label class="form-field">
                                <span>Phòng khách</span>
                                <input type="file" name="vr_living_room" accept="image/*">
                            </label>
                            <label class="form-field">
                                <span>Phòng bếp</span>
                                <input type="file" name="vr_kitchen" accept="image/*">
                            </label>
                            <label class="form-field">
                                <span>Phòng ngủ</span>
                                <input type="file" name="vr_bedroom" accept="image/*">
                            </label>
                        </div>

                        <?php if (!empty($existingVrScenes)): ?>
                            <div class="media-grid">
                                <?php foreach ($existingVrScenes as $scene): ?>
                                    <label class="media-card">
                                        <img src="<?= htmlspecialchars($scene['duong_dan_anh']) ?>" alt="<?= htmlspecialchars($scene['ten_goc_nhin']) ?>">
                                        <span>Gỡ cảnh <?= htmlspecialchars($scene['ten_goc_nhin']) ?></span>
                                        <input type="checkbox" name="remove_vr_ids[]" value="<?= (int) $scene['id'] ?>">
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?= $isEditMode ? 'Lưu thay đổi' : 'Tạo phòng trọ' ?></button>
                        <?php if ($isEditMode): ?>
                            <a href="index.php?page=detail&id=<?= (int) $editingRoom['id'] ?>" class="btn btn-outline">Xem trang chi tiết</a>
                            <a href="index.php?page=vr&id=<?= (int) $editingRoom['id'] ?>" class="btn btn-outline">Xem 360 VR</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="admin-list-panel">
                <div class="section-heading">
                    <div>
                        <h2>Danh sách phòng trọ</h2>
                        <p>Theo dõi dữ liệu đang có, số ảnh, số cảnh 360 và thao tác nhanh.</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Phòng</th>
                                <th>Địa chỉ</th>
                                <th>Giá</th>
                                <th>Ảnh</th>
                                <th>360 VR</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($adminRooms)): ?>
                                <tr>
                                    <td colspan="7" class="empty-cell">Chưa có dữ liệu phòng trọ.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($adminRooms as $roomItem): ?>
                                <tr>
                                    <td>
                                        <div class="table-room">
                                            <img src="<?= htmlspecialchars($roomItem['anh_dai_dien'] ?: 'assets/images/rooms/placeholder.jpg') ?>" alt="<?= htmlspecialchars($roomItem['ten_phong']) ?>">
                                            <div>
                                                <strong><?= htmlspecialchars($roomItem['ten_phong']) ?></strong>
                                                <span>Mã: <?= htmlspecialchars($roomItem['so']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($roomItem['dia_chi'] ?: 'Chưa có dữ liệu') ?></td>
                                    <td><?= adminCurrency($roomItem['gia']) ?> đ</td>
                                    <td><?= (int) $roomItem['so_anh'] ?> ảnh</td>
                                    <td><?= (int) $roomItem['so_canh_vr'] ?> cảnh</td>
                                    <td>
                                        <span class="status-pill <?= $roomItem['trang_thai'] === 'con_trong' ? 'status-available' : 'status-rented' ?>">
                                            <?= $roomItem['trang_thai'] === 'con_trong' ? 'Còn trống' : 'Đã thuê' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a class="btn btn-outline btn-sm" href="index.php?page=admin&edit=<?= (int) $roomItem['id'] ?>">Sửa</a>
                                            <a class="btn btn-outline btn-sm" href="index.php?page=detail&id=<?= (int) $roomItem['id'] ?>">Chi tiết</a>
                                            <form method="post" onsubmit="return confirm('Bạn có chắc muốn xóa phòng trọ này không?');">
                                                <input type="hidden" name="form_action" value="delete_room">
                                                <input type="hidden" name="room_id" value="<?= (int) $roomItem['id'] ?>">
                                                <button type="submit" class="btn btn-outline btn-sm btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../home/partials/footer.php'; ?>
</body>
</html>
