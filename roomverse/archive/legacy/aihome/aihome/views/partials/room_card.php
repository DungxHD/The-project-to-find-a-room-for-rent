<?php
/**
 * Card hiển thị 1 phòng trọ. Biến $room được truyền vào trước khi include,
 * cấu trúc giống dữ liệu trả về từ PhongTroModel::search().
 */
$isNew = isset($room['created_at']) && (strtotime($room['created_at']) >= strtotime('-7 days'));
$rating = $room['diem_trung_binh'] ?? null;
$ratingCount = (int)($room['so_luot_danh_gia'] ?? 0);
$distance = $room['khoang_cach'] ?? null;
$image = !empty($room['anh_dai_dien']) ? $room['anh_dai_dien'] : 'assets/images/rooms/placeholder.jpg';
?>
<article class="room-card" data-id="<?= (int)$room['id'] ?>">
    <div class="room-card-media">
        <?php if ($isNew): ?><span class="badge badge-new">Mới</span><?php endif; ?>
        <button class="room-fav-btn" aria-label="Yêu thích">♡</button>
        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($room['ten_phong']) ?>" loading="lazy">
    </div>

    <div class="room-card-body">
        <h3 class="room-title"><?= htmlspecialchars($room['ten_phong']) ?></h3>
        <p class="room-address">📍 <?= htmlspecialchars($room['dia_chi'] ?? $room['ten_dia_diem']) ?></p>
        <p class="room-price"><?= number_format((float)$room['gia'], 0, ',', '.') ?> đ/tháng</p>

        <div class="room-meta">
            <?php if ($distance !== null): ?>
                <span class="room-distance">📶 <?= number_format((float)$distance, 1) ?> km</span>
            <?php endif; ?>
            <?php if ($rating !== null): ?>
                <span class="room-rating">⭐ <?= htmlspecialchars((string)$rating) ?> (<?= $ratingCount ?>)</span>
            <?php else: ?>
                <span class="room-rating room-rating-empty">Chưa có đánh giá</span>
            <?php endif; ?>
        </div>

        <div class="room-actions">
            <button class="btn btn-outline btn-sm">🕶 Xem phòng VR</button>
            <a class="btn btn-outline btn-sm"
               href="<?= htmlspecialchars($room['link_google'] ?? '#') ?>"
               target="_blank" rel="noopener">🗺 Bản đồ</a>
        </div>
    </div>
</article>
