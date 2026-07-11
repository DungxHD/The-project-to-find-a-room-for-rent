<?php
// =========================================================
// API: XU LY CAU HOI - LUA CHON - PHAN TICH - VIDEO
// =========================================================
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// ---------------------------------------------------------
// 1) LAY CAU HOI THEO id_ai_hoi (khong dung stt de dieu huong nua)
// ---------------------------------------------------------
if ($action === 'get_question') {

    // >>> SUA O DAY: doi $_GET['stt'] thanh $_GET['id']
    $id_ai_hoi = intval($_GET['id'] ?? 1);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id_ai_hoi, stt, thoi_gian_hien_tai, noi_dung_hoi, lua_chon_1, lua_chon_2, lua_chon_3
         FROM ai_hoi WHERE id_ai_hoi = ? LIMIT 1"
    ); // >>> SUA O DAY: WHERE stt = ? -> WHERE id_ai_hoi = ?
    mysqli_stmt_bind_param($stmt, "i", $id_ai_hoi);   // >>> SUA O DAY: bind $stt -> bind $id_ai_hoi
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row) {
        echo json_encode(["found" => false]);
        exit;
    }

    $lua_chon = array_values(array_filter(
        [$row['lua_chon_1'], $row['lua_chon_2'], $row['lua_chon_3']],
        fn($c) => $c !== null && $c !== ''
    ));

    echo json_encode([
        "found"        => true,
        "id_ai_hoi"    => (int)$row['id_ai_hoi'],
        "stt"          => (int)$row['stt'],
        "thoi_gian"    => $row['thoi_gian_hien_tai'],
        "noi_dung_hoi" => $row['noi_dung_hoi'],
        "lua_chon"     => $lua_chon
    ]);
    exit;
}

// ---------------------------------------------------------
// 2) NGUOI DUNG CHON XONG -> TRA VE PHAN TICH + VIDEO + CAU HOI KE TIEP (theo nhanh rieng)
// ---------------------------------------------------------
if ($action === 'submit_choice') {
    $id_ai_hoi = intval($_POST['id_ai_hoi'] ?? 0);
    $lua_chon  = trim($_POST['lua_chon'] ?? '');

    // >>> SUA O DAY: bo JOIN ai_hoi (khong can lay stt nua),
    // them cot k.id_cau_hoi_tiep_theo de biet cau hoi tiep theo cua RIENG lua chon nay
    $stmt = mysqli_prepare(
        $conn,
        "SELECT k.id_khao_sat, k.phuong_tien, k.mo_ta, k.diem_cuoi, k.id_cau_hoi_tiep_theo,
                v.duong_dan, v.ten_video
         FROM khao_sat k
         LEFT JOIN video v ON v.id_khao_sat = k.id_khao_sat
         WHERE k.id_ai_hoi = ? AND k.lua_chon = ?
         LIMIT 1"
    );
    mysqli_stmt_bind_param($stmt, "is", $id_ai_hoi, $lua_chon);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row) {
        echo json_encode(["found" => false]);
        exit;
    }

    // >>> SUA O DAY: XOA toan bo doan tinh next_stt = stt + 1 va query SELECT id_ai_hoi WHERE stt = ?
    // (khong can nua vi khong con dieu huong theo stt+1)

    echo json_encode([
        "found"       => true,
        "phuong_tien" => $row['phuong_tien'],
        "mo_ta"       => $row['mo_ta'],
        "diem_cuoi"   => $row['diem_cuoi'],
        "video"       => $row['duong_dan'],
        "ten_video"   => $row['ten_video'],
        // >>> SUA O DAY: next_stt -> next_id, lay thang tu id_cau_hoi_tiep_theo
        "next_id"     => $row['id_cau_hoi_tiep_theo'] ? (int)$row['id_cau_hoi_tiep_theo'] : null
    ]);
    exit;
}

// ---------------------------------------------------------
// 3) LAY DANH GIA PHONG TRO (hien thi o man hinh ket thuc)
// ---------------------------------------------------------
if ($action === 'get_danh_gia') {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT ten_phong_tro, gia_phong, gia_dien, gia_nuoc, tien_coc, tien_ich_khac, danh_gia_chung
         FROM danh_gia_phong_tro
         ORDER BY RAND()
         LIMIT 1"
    );
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row) {
        echo json_encode(["found" => false]);
        exit;
    }

    echo json_encode([
        "found"          => true,
        "ten_phong_tro"  => $row['ten_phong_tro'],
        "gia_phong"      => $row['gia_phong'] !== null ? (int)$row['gia_phong'] : null,
        "gia_dien"       => $row['gia_dien'] !== null ? (int)$row['gia_dien'] : null,
        "gia_nuoc"       => $row['gia_nuoc'] !== null ? (int)$row['gia_nuoc'] : null,
        "tien_coc"       => $row['tien_coc'] !== null ? (int)$row['tien_coc'] : null,
        "tien_ich_khac"  => $row['tien_ich_khac'],
        "danh_gia_chung" => $row['danh_gia_chung']
    ]);
    exit;
}

echo json_encode(["error" => "Hanh dong khong hop le"]);