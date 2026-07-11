<?php

/**
 * Hero banner: chỉ lưu và hiển thị ảnh.
 *
 * Đã sửa:
 * - Cho phép dùng mảng `$heroImages` động từ controller thay vì hard-code.
 * - Tự sinh số lượng dot theo đúng số ảnh để tránh lệch giao diện khi gộp.
 */
$heroImages = $heroImages ?? ['assets/images/hero-banner-1.png'];
?>
<section class="hero-banner" id="heroBanner">
    <button class="hero-arrow hero-arrow-left">&#10094;</button>

    <div class="hero-banner-track">
        <?php foreach ($heroImages as $index => $heroImage): ?>
            <img
                src="<?= htmlspecialchars($heroImage) ?>"
                class="hero-slide <?= $index === 0 ? 'is-active' : '' ?>"
                alt="Banner giới thiệu <?= $index + 1 ?>">
        <?php endforeach; ?>
    </div>

    <button class="hero-arrow hero-arrow-right">&#10095;</button>

    <div class="hero-dots">
        <?php foreach ($heroImages as $index => $heroImage): ?>
            <button class="hero-dot <?= $index === 0 ? 'is-active' : '' ?>" data-index="<?= $index ?>"></button>
        <?php endforeach; ?>
    </div>
</section>
