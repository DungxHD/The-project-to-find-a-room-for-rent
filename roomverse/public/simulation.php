<?php

declare(strict_types=1);

$roomId = isset($_GET['room_id']) && $_GET['room_id'] !== ''
    ? (int) $_GET['room_id']
    : 0;

include __DIR__ . '/../app/Views/simulation/index.php';
