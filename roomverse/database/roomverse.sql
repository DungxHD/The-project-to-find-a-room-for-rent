-- =========================================================
-- ROOMVERSE
-- Database hop nhat tu 2 he thong:
-- 1) AIHome - tim phong tro
-- 2) Song thu - mo phong cuoc song truoc khi thue
-- =========================================================

CREATE DATABASE IF NOT EXISTS `roomverse`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `roomverse`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `living_simulation_room_reviews`;
DROP TABLE IF EXISTS `living_simulation_videos`;
DROP TABLE IF EXISTS `living_simulation_scenarios`;
DROP TABLE IF EXISTS `living_simulation_questions`;
DROP TABLE IF EXISTS `living_simulation_profiles`;
DROP TABLE IF EXISTS `phong_vr_360`;
DROP TABLE IF EXISTS `danh_gia`;
DROP TABLE IF EXISTS `tien_ich`;
DROP TABLE IF EXISTS `hinh_anh`;
DROP TABLE IF EXISTS `phong_tro`;
DROP TABLE IF EXISTS `toa_do`;
DROP TABLE IF EXISTS `chu_tro`;
DROP TABLE IF EXISTS `doi_tuong`;
DROP TABLE IF EXISTS `loai_dia_diem`;
DROP TABLE IF EXISTS `tai_khoan`;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- KHOI TIM PHONG TRO
-- =========================================================

CREATE TABLE `tai_khoan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ten_dang_nhap` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vai_tro` enum('admin','chu_tro','nguoi_dung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chu_tro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `doi_tuong` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_doi_tuong` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `loai_dia_diem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_loai` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `toa_do` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_dia_diem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_loai` int NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `link_google` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_loai` (`id_loai`),
  CONSTRAINT `toa_do_ibfk_1` FOREIGN KEY (`id_loai`) REFERENCES `loai_dia_diem` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `phong_tro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_phong` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia` decimal(12,0) NOT NULL,
  `id_toa_do` int NOT NULL,
  `dien_tich` decimal(6,2) DEFAULT NULL,
  `tien_coc` decimal(12,0) DEFAULT NULL,
  `so_nguoi_toi_da` int DEFAULT NULL,
  `trang_thai` enum('con_trong','da_thue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'con_trong',
  `id_doi_tuong` int DEFAULT NULL,
  `gioi_tinh` enum('nam','nu','tat_ca') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tat_ca',
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `id_tai_khoan` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_toa_do` (`id_toa_do`),
  KEY `id_doi_tuong` (`id_doi_tuong`),
  KEY `id_tai_khoan` (`id_tai_khoan`),
  CONSTRAINT `phong_tro_ibfk_1` FOREIGN KEY (`id_toa_do`) REFERENCES `toa_do` (`id`),
  CONSTRAINT `phong_tro_ibfk_2` FOREIGN KEY (`id_doi_tuong`) REFERENCES `doi_tuong` (`id`),
  CONSTRAINT `phong_tro_ibfk_3` FOREIGN KEY (`id_tai_khoan`) REFERENCES `tai_khoan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chu_tro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ho_va_ten` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cccd` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_tai_khoan_ngan_hang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_tai_khoan` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tai_khoan` (`id_tai_khoan`),
  CONSTRAINT `chu_tro_ibfk_1` FOREIGN KEY (`id_tai_khoan`) REFERENCES `tai_khoan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hinh_anh` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_phong` int NOT NULL,
  `duong_dan` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_phong` (`id_phong`),
  CONSTRAINT `hinh_anh_ibfk_1` FOREIGN KEY (`id_phong`) REFERENCES `phong_tro` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tien_ich` (
  `id_tien_ich` int NOT NULL AUTO_INCREMENT,
  `id_phong` int NOT NULL,
  `wifi` tinyint(1) NOT NULL DEFAULT '0',
  `dieu_hoa` tinyint(1) NOT NULL DEFAULT '0',
  `may_lanh` tinyint(1) NOT NULL DEFAULT '0',
  `may_giat` tinyint(1) NOT NULL DEFAULT '0',
  `may_say` tinyint(1) NOT NULL DEFAULT '0',
  `tu_lanh` tinyint(1) NOT NULL DEFAULT '0',
  `giuong` tinyint(1) NOT NULL DEFAULT '1',
  `tu_quan_ao` tinyint(1) NOT NULL DEFAULT '0',
  `ban_hoc` tinyint(1) NOT NULL DEFAULT '0',
  `ghe` tinyint(1) NOT NULL DEFAULT '0',
  `rem_cua` tinyint(1) NOT NULL DEFAULT '0',
  `ke_bep` tinyint(1) NOT NULL DEFAULT '0',
  `may_nuoc_nong` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tien_ich`),
  KEY `id_phong` (`id_phong`),
  CONSTRAINT `tien_ich_ibfk_1` FOREIGN KEY (`id_phong`) REFERENCES `phong_tro` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `danh_gia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_phong` int NOT NULL,
  `id_tai_khoan` int DEFAULT NULL,
  `so_sao` tinyint NOT NULL,
  `noi_dung` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_phong` (`id_phong`),
  CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`id_phong`) REFERENCES `phong_tro` (`id`),
  CONSTRAINT `danh_gia_chk_1` CHECK ((`so_sao` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `phong_vr_360` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_phong` int NOT NULL,
  `ten_goc_nhin` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duong_dan_anh` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_phong` (`id_phong`),
  CONSTRAINT `fk_vr_phong` FOREIGN KEY (`id_phong`) REFERENCES `phong_tro` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- KHOI SONG THU TRUOC KHI THUE
-- Da doi ten bang de khong xung dot voi he thong tim phong.
-- =========================================================

CREATE TABLE `living_simulation_profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audience_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `living_simulation_questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `profile_id` int DEFAULT NULL,
  `sequence_no` int NOT NULL,
  `current_time` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `living_simulation_questions_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `living_simulation_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `living_simulation_scenarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `option_label` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `outcome` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_question_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `next_question_id` (`next_question_id`),
  CONSTRAINT `living_simulation_scenarios_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `living_simulation_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `living_simulation_scenarios_ibfk_2` FOREIGN KEY (`next_question_id`) REFERENCES `living_simulation_questions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `living_simulation_videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `scenario_id` int NOT NULL,
  `video_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scenario_id` (`scenario_id`),
  CONSTRAINT `living_simulation_videos_ibfk_1` FOREIGN KEY (`scenario_id`) REFERENCES `living_simulation_scenarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `living_simulation_room_reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int DEFAULT NULL,
  `room_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_price` int DEFAULT NULL COMMENT 'Giá thuê phòng / tháng (VNĐ)',
  `electricity_price` int DEFAULT NULL COMMENT 'Giá điện / kWh (VNĐ)',
  `water_price` int DEFAULT NULL COMMENT 'Giá nước / khối hoặc / người (VNĐ)',
  `deposit_amount` int DEFAULT NULL COMMENT 'Tiền đặt cọc (VNĐ)',
  `extra_utilities` text COLLATE utf8mb4_unicode_ci COMMENT 'Wifi, chỗ để xe, thang máy, an ninh...',
  `overall_review` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhận xét tổng quan',
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `living_simulation_room_reviews_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `phong_tro` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- DU LIEU MAU CHO PHAN TIM PHONG
-- =========================================================

INSERT INTO `tai_khoan` (`id`, `ho_ten`, `sdt`, `email`, `ten_dang_nhap`, `mat_khau`, `vai_tro`) VALUES
  (1, 'Nguyễn Văn A', '0900000001', 'chutro1@example.com', 'chutro1', '$2y$10$examplehashexamplehashexamplehas', 'chu_tro');

INSERT INTO `chu_tro` (`id`, `ho_va_ten`, `email`, `cccd`, `ngay_sinh`, `dia_chi`, `avatar`, `so_dien_thoai`, `so_tai_khoan_ngan_hang`, `id_tai_khoan`) VALUES
  (1, 'Nguyễn Văn A', 'chutro1@example.com', '001099001234', '1990-05-12', 'Cầu Giấy, Hà Nội', 'avatar1.jpg', '0900000001', '0011001234567', 1);

INSERT INTO `doi_tuong` (`id`, `ten_doi_tuong`) VALUES
  (1, 'Sinh viên'),
  (2, 'Người đi làm');

INSERT INTO `loai_dia_diem` (`id`, `ten_loai`) VALUES
  (1, 'Khu vực'),
  (2, 'Vị trí phòng trọ');

INSERT INTO `toa_do` (`id`, `ten_dia_diem`, `id_loai`, `latitude`, `longitude`, `link_google`, `dia_chi`) VALUES
  (1, 'FPT Polytechnic Thái Nguyên', 1, 21.5957090, 105.8118490, 'https://maps.app.goo.gl/xDY3doEd7x2t3H7V7', 'Đường đê Mỏ Bạch, tổ 10 phường, Quyết Thắng, Thái Nguyên'),
  (2, 'Quận Đống Đa, Hà Nội', 1, 21.0136000, 105.8248000, 'https://maps.google.com/?q=Dong+Da+Ha+Noi', 'Đống Đa, Hà Nội'),
  (3, 'Quận Ba Đình, Hà Nội', 1, 21.0350000, 105.8140000, 'https://maps.google.com/?q=Ba+Dinh+Ha+Noi', 'Ba Đình, Hà Nội'),
  (4, 'Quận Thanh Xuân, Hà Nội', 1, 20.9955000, 105.8060000, 'https://maps.google.com/?q=Thanh+Xuan+Ha+Noi', 'Thanh Xuân, Hà Nội'),
  (5, 'Đường Xuân Thủy, Cầu Giấy, Hà Nội', 2, 21.0369000, 105.7830000, 'https://maps.google.com/?q=21.0369,105.7830', 'Đường Xuân Thủy, Cầu Giấy, Hà Nội'),
  (6, 'Phong tro Ha An', 2, 21.5954870, 105.8135940, 'https://maps.app.goo.gl/1ZAkVTDoqr4Ecfuo9', 'Phòng trọ của Hà, Phan Đình Phùng, Thái Nguyên'),
  (7, 'Chùa Láng, Đống Đa, Hà Nội', 2, 21.0231000, 105.8138000, 'https://maps.google.com/?q=21.0231,105.8138', 'Chùa Láng, Đống Đa, Hà Nội'),
  (8, 'Kim Mã Thượng, Ba Đình, Hà Nội', 2, 21.0378000, 105.8188000, 'https://maps.google.com/?q=21.0378,105.8188', 'Kim Mã Thượng, Ba Đình, Hà Nội');

INSERT INTO `phong_tro` (`id`, `ten_phong`, `so`, `gia`, `id_toa_do`, `dien_tich`, `tien_coc`, `so_nguoi_toi_da`, `trang_thai`, `id_doi_tuong`, `gioi_tinh`, `mo_ta`, `id_tai_khoan`, `created_at`) VALUES
  (1, 'Phòng trọ gần ĐH Quốc Gia', 'P101', 2800000, 5, 20.00, 2800000, 2, 'con_trong', 1, 'tat_ca', 'Phòng gác lửng gần ĐH Quốc Gia, đầy đủ nội thất.', 1, '2026-07-07 13:59:39'),
  (2, 'Phòng trọ của Hà An', 'P202', 1200000, 6, 22.00, 1200000, 2, 'con_trong', 1, 'tat_ca', 'Phòng thoáng mát ,gần cao đẳng fpt', 1, '2026-07-06 13:59:39'),
  (3, 'Phòng trọ gần ĐH Sư Phạm', 'P303', 2600000, 7, 18.00, 2600000, 1, 'con_trong', 1, 'tat_ca', 'Phòng gác lửng có sofa, gần ĐH Sư Phạm.', 1, '2026-07-05 13:59:39'),
  (4, 'Phòng trọ Kim Mã Thượng', 'P404', 3000000, 8, 25.00, 3000000, 2, 'con_trong', 2, 'tat_ca', 'Phòng rộng rãi, ban công thoáng, gần Kim Mã Thượng.', 1, '2026-07-04 13:59:39');

INSERT INTO `hinh_anh` (`id`, `id_phong`, `duong_dan`) VALUES
  (1, 1, 'assets/images/rooms/room1.jpg'),
  (2, 2, 'assets/images/rooms/room2.jpg'),
  (3, 3, 'assets/images/rooms/room3.jpg'),
  (4, 4, 'assets/images/rooms/room4.jpg');

INSERT INTO `tien_ich` (`id_tien_ich`, `id_phong`, `wifi`, `dieu_hoa`, `may_lanh`, `may_giat`, `may_say`, `tu_lanh`, `giuong`, `tu_quan_ao`, `ban_hoc`, `ghe`, `rem_cua`, `ke_bep`, `may_nuoc_nong`) VALUES
  (1, 1, 1, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 0, 1),
  (2, 2, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 1),
  (3, 3, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1),
  (4, 4, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

INSERT INTO `danh_gia` (`id`, `id_phong`, `id_tai_khoan`, `so_sao`, `noi_dung`, `created_at`) VALUES
  (1, 1, NULL, 5, 'Phòng đẹp, chủ nhiệt tình', '2026-07-08 13:59:40'),
  (2, 1, NULL, 4, 'Ổn', '2026-07-08 13:59:40'),
  (3, 1, NULL, 5, 'Tốt', '2026-07-08 13:59:40'),
  (4, 2, NULL, 5, 'Rất hài lòng', '2026-07-08 13:59:40'),
  (5, 2, NULL, 4, 'Giá hợp lý', '2026-07-08 13:59:40'),
  (6, 3, NULL, 4, 'Gần trường, tiện đi lại', '2026-07-08 13:59:40'),
  (7, 4, NULL, 5, 'View đẹp', '2026-07-08 13:59:40'),
  (8, 4, NULL, 5, 'Đáng tiền', '2026-07-08 13:59:40');

-- =========================================================
-- DU LIEU MAU CHO PHAN SONG THU
-- =========================================================

INSERT INTO `living_simulation_profiles` (`id`, `full_name`, `age`, `gender`, `audience_type`) VALUES
  (1, 'Nguyen Van A', 20, 'Nam', 'Sinh vien');

INSERT INTO `living_simulation_questions` (`id`, `profile_id`, `sequence_no`, `current_time`, `question_text`, `option_1`, `option_2`, `option_3`) VALUES
  (1, 1, 1, '07:00', 'Bây giờ là 7:00, bạn muốn làm gì?', '', 'Đi học', ''),
  (3, 1, 2, '18:00', 'Bây giờ là 18:00, trời đã tối, bạn muốn làm gì?', 'Đi ăn tối', '', 'Đi tập gym'),
  (5, 1, 2, '12:00', 'Bây giờ là 12:00, giờ nghỉ trưa ở công ty, bạn muốn làm gì?', 'Ăn cơm văn phòng', 'Nghỉ trưa tại bàn', NULL),
  (6, 1, 2, '12:00', 'Bây giờ là 12:00, vừa mới tan học, bạn muốn đi đâu?', 'Tôi muốn đi ăn', '', NULL);

INSERT INTO `living_simulation_scenarios` (`id`, `question_id`, `option_label`, `transport`, `description`, `outcome`, `next_question_id`) VALUES
  (2, 1, 'Đi học', 'Xe máy', '\nHôm nay trời mưa nên thời gian đi sẽ trễ 10 phút.\nThời gian dự kiến mất khoảng 20 phút.\nĐi từ trọ khoảng 2km nữa thì có chợ (chỗ này hay tắc đường kẹt xe).\nĐi tiếp 5km nữa thì có công an và đèn giao thông.\nĐi tiếp nữa và rẽ phải sẽ đến trường.', 'Vào trường và lên lớp học code', 6),
  (6, 6, 'Tôi muốn đi ăn', 'Xe máy', 'Người thuê trọ lấy xe máy ra quán ăn quen thuộc gần khu trọ.\nĐường phố lên đèn, không khí buổi tối mát mẻ hơn ban ngày.\nThời gian di chuyển dự kiến khoảng 12 phút.\nQuán khá đông vì là giờ cao điểm ăn tối.', 'Ngồi ăn tối cùng vài người bạn ở trọ', 3),
  (8, 3, 'Đi tập gym', 'Xe máy', 'Người thuê trọ lấy xe máy đến phòng gym gần khu trọ.\nThời gian di chuyển khoảng 10 phút.\nTập luyện khoảng 1 tiếng với các bài cơ bản.\nSau khi tập xong cảm thấy tỉnh táo và có năng lượng hơn.', 'Về trọ tắm rửa sau buổi tập', NULL);

INSERT INTO `living_simulation_videos` (`id`, `scenario_id`, `video_name`, `video_path`) VALUES
  (1, 6, 'Hành trình đi học', 'assets/videos/simulation/di_hoc_2.mp4'),
  (2, 2, 'Hành trình đi học', 'assets/videos/simulation/di_hoc.mp4'),
  (3, 8, 'Hành trình đi học', 'assets/videos/simulation/di_hoc_3.mp4');

-- Da sua:
-- - Gắn dữ liệu đánh giá sống thử với đúng `room_id` của hệ thống tìm phòng.
-- - Đồng bộ tên phòng, giá thuê và tiền cọc để module mô phỏng không còn hiển thị
--   dữ liệu mẫu rời rạc từ database cũ sau khi hợp nhất.
INSERT INTO `living_simulation_room_reviews` (`id`, `room_id`, `room_name`, `room_price`, `electricity_price`, `water_price`, `deposit_amount`, `extra_utilities`, `overall_review`) VALUES
  (1, 1, 'Phòng trọ gần ĐH Quốc Gia', 2800000, 3800, 100000, 2800000, 'Wifi miễn phí, máy giặt, tủ lạnh, giường, bàn học, nước nóng.', 'Phòng phù hợp cho sinh viên cần ở gần trường, tiện nghi đủ dùng và di chuyển thuận tiện mỗi ngày.'),
  (2, 2, 'Phòng trọ của Hà An', 1200000, 3500, 80000, 1200000, 'Wifi miễn phí, tủ lạnh, giường, tủ quần áo, rèm cửa.', 'Mức giá mềm, phù hợp sinh viên tại Thái Nguyên, chi phí sinh hoạt dễ kiểm soát và không gian khá thoáng.'),
  (3, 3, 'Phòng trọ gần ĐH Sư Phạm', 2600000, 3600, 90000, 2600000, 'Wifi miễn phí, máy giặt, tủ lạnh, giường, bàn học, kệ bếp, nước nóng.', 'Phòng phù hợp người cần ở gần trường, có đủ tiện ích cơ bản để học tập và sinh hoạt lâu dài.'),
  (4, 4, 'Phòng trọ Kim Mã Thượng', 3000000, 4000, 120000, 3000000, 'Wifi miễn phí, máy giặt, máy sấy, tủ lạnh, ban công thoáng, khu vực an ninh.', 'Không gian rộng hơn, hợp với người đi làm hoặc ở ghép 2 người, bù lại chi phí tổng thể cao hơn các phòng còn lại.');

ALTER TABLE `tai_khoan` AUTO_INCREMENT = 2;
ALTER TABLE `chu_tro` AUTO_INCREMENT = 2;
ALTER TABLE `doi_tuong` AUTO_INCREMENT = 3;
ALTER TABLE `loai_dia_diem` AUTO_INCREMENT = 3;
ALTER TABLE `toa_do` AUTO_INCREMENT = 9;
ALTER TABLE `phong_tro` AUTO_INCREMENT = 5;
ALTER TABLE `hinh_anh` AUTO_INCREMENT = 5;
ALTER TABLE `tien_ich` AUTO_INCREMENT = 5;
ALTER TABLE `danh_gia` AUTO_INCREMENT = 9;
ALTER TABLE `living_simulation_profiles` AUTO_INCREMENT = 2;
ALTER TABLE `living_simulation_questions` AUTO_INCREMENT = 7;
ALTER TABLE `living_simulation_scenarios` AUTO_INCREMENT = 9;
ALTER TABLE `living_simulation_videos` AUTO_INCREMENT = 4;
ALTER TABLE `living_simulation_room_reviews` AUTO_INCREMENT = 5;
