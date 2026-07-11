<?php

declare(strict_types=1);

$currentPage = 'vr';
$room = $room ?? null;
$vrScenes = $room['vr_scenes'] ?? [];
$vrConfig = ['default' => ['firstScene' => ''], 'scenes' => []];

if ($room && !empty($vrScenes)) {
    $firstSceneId = 'scene-' . (int) $vrScenes[0]['id'];
    $vrConfig['default']['firstScene'] = $firstSceneId;

    foreach ($vrScenes as $scene) {
        $vrConfig['scenes']['scene-' . (int) $scene['id']] = [
            'title' => $scene['ten_goc_nhin'],
            'type' => 'equirectangular',
            'panorama' => $scene['duong_dan_anh'],
            'autoLoad' => true,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoomVerse - Xem 360 VR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
</head>
<body class="page-portal page-vr">
    <?php include __DIR__ . '/../home/partials/header.php'; ?>

    <main class="portal-main">
        <?php if (!$room): ?>
            <section class="container portal-section">
                <div class="empty-state">
                    <h1>Không tìm thấy phòng để xem 360 VR</h1>
                    <p>Vui lòng quay lại trang chủ hoặc vào phần quản trị để bổ sung dữ liệu.</p>
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
                        <span class="portal-kicker">Tham quan 360 VR</span>
                        <h1><?= htmlspecialchars($room['ten_phong']) ?></h1>
                        <p><?= htmlspecialchars((string) ($room['dia_chi'] ?: $room['ten_dia_diem'] ?: 'Chưa có dữ liệu')) ?></p>
                        <div class="detail-quick-meta">
                            <span><?= number_format((float) $room['gia'], 0, ',', '.') ?> đ/tháng</span>
                            <span><?= count($vrScenes) ?> cảnh 360</span>
                            <span><?= !empty($room['images']) ? count($room['images']) . ' ảnh thường' : 'Chưa có dữ liệu ảnh thường' ?></span>
                        </div>
                    </div>
                    <div class="portal-hero-actions">
                        <a class="btn btn-outline" href="index.php?page=detail&id=<?= (int) $room['id'] ?>">Xem chi tiết</a>
                        <a class="btn btn-primary" href="simulation.php?room_id=<?= (int) $room['id'] ?>">Sống thử AI</a>
                    </div>
                </div>
            </section>

            <section class="container portal-section">
                <?php if (empty($vrScenes)): ?>
                    <div class="empty-state">
                        <h2>Chưa có dữ liệu ảnh 360</h2>
                        <p>Phòng này chưa được tải lên ảnh panorama 360. Bạn có thể vào phần quản trị để bổ sung dữ liệu.</p>
                        <div class="empty-actions">
                            <a class="btn btn-primary" href="index.php?page=admin&edit=<?= (int) $room['id'] ?>">Bổ sung trong admin</a>
                            <a class="btn btn-outline" href="simulation.php?room_id=<?= (int) $room['id'] ?>">Sống thử AI</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="vr-shell">
                        <div class="vr-toolbar">
                            <div class="vr-scene-tabs">
                                <?php foreach ($vrScenes as $index => $scene): ?>
                                    <button
                                        type="button"
                                        class="vr-scene-btn<?= $index === 0 ? ' is-active' : '' ?>"
                                        data-scene-id="scene-<?= (int) $scene['id'] ?>"
                                    >
                                        <?= htmlspecialchars($scene['ten_goc_nhin']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="vr-toolbar-actions">
                                <button type="button" class="btn btn-outline" id="btnVrFullscreen">Toàn màn hình</button>
                                <a class="btn btn-primary" href="simulation.php?room_id=<?= (int) $room['id'] ?>">Sống thử AI</a>
                            </div>
                        </div>

                        <div class="vr-viewer-shell" id="vrViewerShell">
                            <div id="vrViewer" class="vr-viewer"></div>
                            <div class="vr-overlay-card">
                                <h2><?= htmlspecialchars($room['ten_phong']) ?></h2>
                                <p>Người dùng có thể xoay 360, chuyển cảnh giữa các không gian và bấm “Sống thử AI” để trải nghiệm tiếp.</p>
                                <div class="stack-actions">
                                    <a class="btn btn-primary" href="simulation.php?room_id=<?= (int) $room['id'] ?>">Sống thử AI</a>
                                    <a class="btn btn-outline" href="index.php?page=detail&id=<?= (int) $room['id'] ?>">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../home/partials/footer.php'; ?>

    <?php if ($room && !empty($vrScenes)): ?>
        <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
        <script>
            const vrConfig = <?= json_encode($vrConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
            const viewer = pannellum.viewer("vrViewer", vrConfig);
            const sceneButtons = Array.from(document.querySelectorAll(".vr-scene-btn"));
            const viewerShell = document.getElementById("vrViewerShell");
            const fullscreenButton = document.getElementById("btnVrFullscreen");

            function setActiveSceneButton(sceneId) {
                sceneButtons.forEach((button) => {
                    button.classList.toggle("is-active", button.dataset.sceneId === sceneId);
                });
            }

            sceneButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    const sceneId = button.dataset.sceneId;
                    viewer.loadScene(sceneId);
                    setActiveSceneButton(sceneId);
                });
            });

            fullscreenButton.addEventListener("click", async () => {
                if (!document.fullscreenElement) {
                    await viewerShell.requestFullscreen();
                } else {
                    await document.exitFullscreen();
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
