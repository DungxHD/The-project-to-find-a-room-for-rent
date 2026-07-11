<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="index.php" class="brand">
                <span class="brand-icon">🏠</span>
                <span class="brand-text">
                    <strong>AIHome</strong>
                    <small>Tìm phòng trọ thông minh bằng AI</small>
                </span>
            </a>
            <p class="footer-desc">
                AIHome giúp bạn tìm phòng trọ phù hợp nhanh chóng, tiết kiệm thời gian và chi phí.
            </p>
            <div class="footer-social">
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="Zalo">Z</a>
                <a href="#" aria-label="TikTok">t</a>
                <a href="#" aria-label="Youtube">▶</a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Về AIHome</h4>
            <a href="#">Giới thiệu</a>
            <a href="#">Cách hoạt động</a>
            <a href="#">Tin tức</a>
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
            <h4>Khu vực nổi bật</h4>
            <a href="#">Hà Nội</a>
            <a href="#">TP. Hồ Chí Minh</a>
            <a href="#">Đà Nẵng</a>
            <a href="#">Hải Phòng</a>
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
            &copy; <?= date('Y') ?> AIHome. All rights reserved.
        </div>
    </div>
</footer>
