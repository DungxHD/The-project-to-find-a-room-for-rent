<?php

/**
 * Hero banner: CHỈ lưu và hiển thị ảnh (không lưu tiêu đề/nội dung trong DB).
 * Truyền mảng đường dẫn ảnh vào biến $heroImages trước khi include file này.
 * Ví dụ: $heroImages = ['assets/images/hero-banner-1.jpg', ...];
 */
$heroImages = $heroImages ?? ['assets/images/hero-banner-1.jpg'];
?>
<section class="hero-banner" id="heroBanner">

    <button class="hero-arrow hero-arrow-left">&#10094;</button>

    <div class="hero-banner-track">

        <img src="assets/images/hero-banner-1.png" class="hero-slide is-active">

        <img src="assets/images/hero-banner-2.png" class="hero-slide">

        <!-- <img src="assets/images/hero-banner-3.jpg" class="hero-slide"> -->

    </div>

    <button class="hero-arrow hero-arrow-right">&#10095;</button>

    <div class="hero-dots">
        <button class="hero-dot is-active" data-index="0"></button>
        <button class="hero-dot" data-index="1"></button>
        <button class="hero-dot" data-index="2"></button>
    </div>

</section>