<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomVerse - Sống thử trước khi thuê</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/simulation.css">
</head>
<body data-room-id="<?= isset($roomId) ? (int) $roomId : 0 ?>">
    <header class="app-header">
        <div class="brand">
            <a href="index.php" class="brand-icon" aria-label="Về trang chủ">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <path d="M3 11L12 4L21 11V20H14V14H10V20H3V11Z" stroke="#1565C0" stroke-width="1.8" stroke-linejoin="round" />
                </svg>
            </a>
            <div class="brand-copy">
                <span class="brand-text">ROOMVERSE - MỘT NGÀY SỐNG THỬ Ở TRỌ</span>
                <small class="brand-subtitle">Trải nghiệm cuộc sống trước khi quyết định</small>
            </div>
        </div>
        <div class="clock" id="clock">--:--</div>
    </header>

    <main id="main-stage" class="main-stage">
        <section id="chat-box" class="chat-box hidden">
            <div class="avatar-col">
                <div class="robot-avatar">
                    <svg viewBox="0 0 120 120" width="110" height="110">
                        <circle cx="60" cy="60" r="58" fill="#EAF4FF" />
                        <rect x="35" y="30" width="50" height="42" rx="14" fill="#1565C0" />
                        <circle cx="49" cy="50" r="6" fill="#EAF4FF" />
                        <circle cx="71" cy="50" r="6" fill="#EAF4FF" />
                        <rect x="48" y="62" width="24" height="4" rx="2" fill="#EAF4FF" />
                        <rect x="57" y="16" width="6" height="14" fill="#1565C0" />
                        <circle cx="60" cy="14" r="5" fill="#64B5F6" />
                        <rect x="20" y="78" width="80" height="26" rx="13" fill="#1565C0" />
                        <rect x="30" y="86" width="60" height="4" rx="2" fill="#EAF4FF" opacity="0.7" />
                    </svg>
                </div>
                <span class="avatar-label">AI trợ lý</span>
            </div>
            <div class="dialog-col">
                <div class="bubble" id="bubble-question">Đang tải câu hỏi...</div>
                <div class="choices" id="choices"></div>
            </div>
        </section>

        <section id="analysis-box" class="analysis-box hidden">
            <h2 class="analysis-title">Phân tích hành trình thuê trọ</h2>
            <div class="route-line" id="route-line"></div>
            <button id="btn-resume-video" class="btn-resume hidden">Tiếp tục xem video</button>
        </section>
    </main>

    <section id="end-screen" class="end-screen hidden">
        <svg width="70" height="70" viewBox="0 0 24 24" fill="none">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="#1565C0" stroke-width="1.5" stroke-linejoin="round" />
        </svg>
        <h1>Đã hoàn thành một ngày sống thử ở trọ</h1>
        <p>Cảm ơn bạn đã trải nghiệm mô phỏng hôm nay.</p>

        <div id="room-review" class="room-review hidden">
            <h2 class="room-review-title" id="review-name">Đánh giá phòng trọ</h2>
            <div class="review-grid" id="review-grid"></div>
            <p class="review-comment" id="review-comment"></p>
        </div>

        <div class="end-actions">
            <button id="btn-restart">Bắt đầu lại</button>
            <a href="index.php" class="btn-back-home">Quay lại trang chủ</a>
        </div>
    </section>

    <div id="fade-overlay" class="fade-overlay"></div>

    <div id="video-overlay" class="video-overlay">
        <div class="video-frame">
            <video id="video-player" playsinline></video>
            <div class="video-controls">
                <button id="btn-play-pause" class="video-btn video-btn-icon" title="Phát / Tạm dừng">
                    <svg id="icon-pause" viewBox="0 0 24 24" width="18" height="18">
                        <path d="M6 5h4v14H6zM14 5h4v14h-4z" fill="currentColor" />
                    </svg>
                    <svg id="icon-play" viewBox="0 0 24 24" width="18" height="18" style="display:none">
                        <path d="M7 5v14l12-7z" fill="currentColor" />
                    </svg>
                </button>

                <span class="video-time" id="video-time">0:00 / 0:00</span>
                <input type="range" id="video-seek" class="video-seek" min="0" max="100" value="0" step="0.1">

                <button id="btn-back-analysis" class="video-btn video-btn-text" title="Quay lại phân tích">
                    Xem lại phân tích
                </button>
                <button id="btn-skip-next" class="video-btn video-btn-text" title="Sang câu hỏi tiếp theo">
                    Bỏ qua
                </button>
            </div>
        </div>
    </div>

    <script src="assets/js/simulation.js"></script>
</body>
</html>
