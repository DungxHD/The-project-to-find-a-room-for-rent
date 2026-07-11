<?php

declare(strict_types=1);

$currentPage = $currentPage ?? 'home';
$homeHref = 'index.php';
$searchHref = $currentPage === 'home' ? '#search_1' : 'index.php#search_1';
$valueHref = $currentPage === 'home' ? '#product-value' : 'index.php#product-value';
?>
<header class="site-header">
    <div class="container header-inner">
        <a href="<?= $homeHref ?>" class="brand">
            <span class="brand-icon">🏠</span>
            <span class="brand-text">
                <strong>RoomVerse</strong>
                <small>Trải nghiệm cuộc sống trước khi quyết định</small>
            </span>
        </a>

        <nav class="main-nav">
            <a href="<?= $homeHref ?>" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">Trang chủ</a>
            <a href="<?= $searchHref ?>" class="nav-link">Tìm phòng trọ</a>
            <a href="index.php?page=admin" class="nav-link <?= $currentPage === 'admin' ? 'active' : '' ?>">Quản trị</a>
            <a href="simulation.php" class="nav-link">Sống thử AI</a>
            <a href="<?= $valueHref ?>" class="nav-link">Giá trị nổi bật</a>
        </nav>

        <div class="header-actions">
            <button class="icon-btn" title="Thông báo" aria-label="Thông báo">🔔</button>
            <a href="index.php?page=admin" class="btn btn-primary">Vào quản trị</a>
        </div>
    </div>
</header>
