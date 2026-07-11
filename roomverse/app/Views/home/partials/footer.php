<?php

declare(strict_types=1);

$currentPage = $currentPage ?? 'home';
$searchHref = $currentPage === 'home' ? '#search_1' : 'index.php#search_1';
$valueHref = $currentPage === 'home' ? '#product-value' : 'index.php#product-value';
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="index.php" class="brand">
                <span class="brand-icon">🏠</span>
                <span class="brand-text">
                    <strong>RoomVerse</strong>
                    <small>Trải nghiệm cuộc sống trước khi quyết định</small>
                </span>
            </a>
            <p class="footer-desc">
                RoomVerse kết hợp AI tìm phòng, tham quan VR và mô phỏng sống thử trước khi thuê.
            </p>
            <div class="footer-social">
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="Zalo">Z</a>
                <a href="#" aria-label="TikTok">t</a>
                <a href="#" aria-label="Youtube">▶</a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Về RoomVerse</h4>
            <a href="<?= $valueHref ?>">Giá trị nổi bật</a>
            <a href="index.php?page=admin">Trang quản trị</a>
            <a href="simulation.php">Sống thử AI</a>
            <a href="<?= $searchHref ?>">Tìm phòng nhanh</a>
            <a href="#">Liên hệ</a>
        </div>

        <div class="footer-col">
            <h4>Hỗ trợ</h4>
            <a href="#">Hướng dẫn tìm phòng</a>
            <a href="#">Câu hỏi thường gặp</a>
            <a href="#">Chính sách bảo mật</a>
            <a href="#">Điều khoản sử dụng</a>
        </div>

        <div class="footer-col">
            <h4>Tính năng chính</h4>
            <a href="#">Tìm phòng theo GPS</a>
            <a href="#">Matching Score</a>
            <a href="#">So sánh phòng tự động</a>
            <a href="#">Xem 360 VR toàn cảnh</a>
        </div>

        <div class="footer-col footer-newsletter">
            <h4>Đăng ký nhận tin</h4>
            <p>Nhận thông tin phòng trọ mới nhất và ưu đãi hấp dẫn.</p>
            <form class="newsletter-form" onsubmit="return false;">
                <input type="email" placeholder="Nhập email của bạn" required>
                <button type="submit" aria-label="Đăng ký">➤</button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            &copy; <?= date('Y') ?> RoomVerse. Bảo lưu mọi quyền.
        </div>
    </div>
</footer>
