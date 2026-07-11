-- =========================================================
-- DATABASE: MO_PHONG_SONGTHU
-- Mo phong 1 ngay song thu o tro
-- =========================================================

CREATE DATABASE IF NOT EXISTS mo_phong_songthu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mo_phong_songthu;

-- =========================================================
-- BANG 1: NGUOI_DUNG
-- =========================================================
CREATE TABLE nguoi_dung (
    id_user     INT AUTO_INCREMENT PRIMARY KEY,
    ten         VARCHAR(100),
    tuoi        INT,
    gioi_tinh   VARCHAR(10),
    doi_tuong   VARCHAR(50)
) ENGINE=InnoDB;

-- =========================================================
-- BANG 2: AI_HOI  (moi dong la 1 cau hoi trong ngay, "stt" la thu tu)
-- =========================================================
CREATE TABLE ai_hoi (
    id_ai_hoi           INT AUTO_INCREMENT PRIMARY KEY,
    id_user             INT,
    stt                 INT NOT NULL,
    thoi_gian_hien_tai  VARCHAR(10),
    noi_dung_hoi        VARCHAR(255),
    lua_chon_1          VARCHAR(50),
    lua_chon_2          VARCHAR(50),
    lua_chon_3          VARCHAR(50),
    FOREIGN KEY (id_user) REFERENCES nguoi_dung(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- BANG 3: KHAO_SAT  (phan tich ung voi tung lua chon cua tung cau hoi)
-- =========================================================
CREATE TABLE khao_sat (
    id_khao_sat  INT AUTO_INCREMENT PRIMARY KEY,
    id_ai_hoi    INT NOT NULL,
    lua_chon     VARCHAR(50) NOT NULL,
    phuong_tien  VARCHAR(50),
    mo_ta        TEXT,
    diem_cuoi    VARCHAR(150),
    FOREIGN KEY (id_ai_hoi) REFERENCES ai_hoi(id_ai_hoi) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- BANG 4: VIDEO  (video minh hoa ung voi tung khao_sat)
-- =========================================================
CREATE TABLE video (
    id_video     INT AUTO_INCREMENT PRIMARY KEY,
    id_khao_sat  INT NOT NULL,
    ten_video    VARCHAR(150),
    duong_dan    VARCHAR(255),
    FOREIGN KEY (id_khao_sat) REFERENCES khao_sat(id_khao_sat) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- DU LIEU MAU
-- =========================================================

INSERT INTO nguoi_dung (ten, tuoi, gioi_tinh, doi_tuong)
VALUES ('Nguyen Van A', 20, 'Nam', 'Sinh vien');

-- ---------- STAGE 1: 7:00 ----------
INSERT INTO ai_hoi (id_user, stt, thoi_gian_hien_tai, noi_dung_hoi, lua_chon_1, lua_chon_2, lua_chon_3)
VALUES (1, 1, '07:00', 'Bây giờ là 7:00, bạn muốn làm gì?', 'Đi làm', 'Đi học', 'Đi chơi');

INSERT INTO khao_sat (id_ai_hoi, lua_chon, phuong_tien, mo_ta, diem_cuoi) VALUES
(1, 'Đi làm', 'Xe máy',
 'Người thuê trọ lấy xe máy và đi làm.\nHôm nay trời mưa nên thời gian đi sẽ trễ 10 phút.\nThời gian dự kiến mất khoảng 25 phút.\nĐi được khoảng 3km thì gặp đèn đỏ ở ngã tư lớn.\nRẽ trái thêm 1km nữa là tới công ty.',
 'Đến công ty và bắt đầu giờ làm việc'),
(1, 'Đi học', 'Xe máy',
 'Người thuê trọ sẽ lấy xe máy và đi.\nHôm nay trời mưa nên thời gian đi sẽ trễ 10 phút.\nThời gian dự kiến mất khoảng 20 phút.\nĐi từ trọ khoảng 2km nữa thì có chợ (chỗ này hay tắc đường kẹt xe).\nĐi tiếp 5km nữa thì có công an và đèn giao thông.\nĐi tiếp nữa và rẽ phải sẽ đến trường.',
 'Vào trường và lên lớp học code'),
(1, 'Đi chơi', 'Xe máy',
 'Người thuê trọ lấy xe máy và đi chơi cùng bạn bè.\nTrời mưa nên phải mặc áo mưa trước khi đi.\nThời gian di chuyển dự kiến khoảng 15 phút.\nĐi ngang qua công viên gần khu trọ, không khí mát mẻ sau mưa.',
 'Đến điểm hẹn và bắt đầu buổi đi chơi');

INSERT INTO video (id_khao_sat, ten_video, duong_dan) VALUES
(1, 'Hành trình đi làm', 'videos/di_lam.mp4'),
(2, 'Hành trình đi học', 'videos/di_hoc.mp4'),
(3, 'Hành trình đi chơi', 'videos/di_choi.mp4');

-- ---------- STAGE 2: 12:00 ----------
INSERT INTO ai_hoi (id_user, stt, thoi_gian_hien_tai, noi_dung_hoi, lua_chon_1, lua_chon_2, lua_chon_3)
VALUES (1, 2, '12:00', 'Bây giờ là 12:00, vừa mới tan học, bạn muốn đi đâu?', 'Tôi muốn đi ăn', 'Tôi muốn đi về', NULL);

INSERT INTO khao_sat (id_ai_hoi, lua_chon, phuong_tien, mo_ta, diem_cuoi) VALUES
(2, 'Tôi muốn đi ăn', 'Đi bộ',
 'Người thuê trọ ra khỏi trường và đi bộ tìm quán ăn gần đó.\nTrời đã tạnh mưa, đường bắt đầu đông người vào giờ trưa.\nThời gian dự kiến khoảng 10 phút.\nQuán cơm quen thuộc cách trường khoảng 300m.',
 'Ngồi ăn trưa tại quán cơm gần trường'),
(2, 'Tôi muốn đi về', 'Xe máy',
 'Người thuê trọ lấy xe máy và chạy thẳng về phòng trọ.\nĐường về ít xe hơn lúc sáng vì đã hết giờ cao điểm.\nThời gian dự kiến khoảng 15 phút.\nĐi ngang qua khu chợ lúc sáng nhưng giờ đã vãn người.',
 'Về đến phòng trọ và nghỉ ngơi');

INSERT INTO video (id_khao_sat, ten_video, duong_dan) VALUES
(4, 'Đi ăn trưa', 'videos/di_an.mp4'),
(5, 'Về phòng trọ', 'videos/ve_phong.mp4');

-- Ghi chu: ban co the them tiep cac stage 3, 4... (VD: 18:00 an toi, 22:00 di ngu)
-- bang cach INSERT them vao ai_hoi voi stt tang dan, roi them khao_sat + video tuong ung.
