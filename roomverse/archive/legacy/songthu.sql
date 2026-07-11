-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for mo_phong_songthu
CREATE DATABASE IF NOT EXISTS `mo_phong_songthu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mo_phong_songthu`;

-- Dumping structure for table mo_phong_songthu.ai_hoi
CREATE TABLE IF NOT EXISTS `ai_hoi` (
  `id_ai_hoi` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `stt` int NOT NULL,
  `thoi_gian_hien_tai` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noi_dung_hoi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lua_chon_1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lua_chon_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lua_chon_3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_ai_hoi`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `ai_hoi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `nguoi_dung` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mo_phong_songthu.ai_hoi: ~4 rows (approximately)
INSERT INTO `ai_hoi` (`id_ai_hoi`, `id_user`, `stt`, `thoi_gian_hien_tai`, `noi_dung_hoi`, `lua_chon_1`, `lua_chon_2`, `lua_chon_3`) VALUES
	(1, 1, 1, '07:00', 'Bây giờ là 7:00, bạn muốn làm gì?', '', 'Đi học', ''),
	(3, 1, 2, '18:00', 'Bây giờ là 18:00, trời đã tối, bạn muốn làm gì?', 'Đi ăn tối', '', 'Đi tập gym'),
	(5, 1, 2, '12:00', 'Bây giờ là 12:00, giờ nghỉ trưa ở công ty, bạn muốn làm gì?', 'Ăn cơm văn phòng', 'Nghỉ trưa tại bàn', NULL),
	(6, 1, 2, '12:00', 'Bây giờ là 12:00, vừa mới tan học, bạn muốn đi đâu?', 'Tôi muốn đi ăn', '', NULL);

-- Dumping structure for table mo_phong_songthu.danh_gia_phong_tro
CREATE TABLE IF NOT EXISTS `danh_gia_phong_tro` (
  `id_danh_gia` int NOT NULL AUTO_INCREMENT,
  `ten_phong_tro` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia_phong` int DEFAULT NULL COMMENT 'Giá thuê phòng / tháng (VNĐ)',
  `gia_dien` int DEFAULT NULL COMMENT 'Giá điện / kWh (VNĐ)',
  `gia_nuoc` int DEFAULT NULL COMMENT 'Giá nước / khối hoặc / người (VNĐ)',
  `tien_coc` int DEFAULT NULL COMMENT 'Tiền đặt cọc (VNĐ)',
  `tien_ich_khac` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Wifi, chỗ để xe, thang máy, an ninh...',
  `danh_gia_chung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nhận xét tổng quan',
  PRIMARY KEY (`id_danh_gia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mo_phong_songthu.danh_gia_phong_tro: ~3 rows (approximately)
INSERT INTO `danh_gia_phong_tro` (`id_danh_gia`, `ten_phong_tro`, `gia_phong`, `gia_dien`, `gia_nuoc`, `tien_coc`, `tien_ich_khac`, `danh_gia_chung`) VALUES
	(1, 'Phòng trọ khu Bình Thạnh', 2500000, 3800, 100000, 2500000, 'Wifi miễn phí, chỗ để xe riêng, có thang máy, camera an ninh 24/24', 'Phòng thoáng mát, gần trường học và chợ, phù hợp cho sinh viên'),
	(2, 'Phòng trọ khu Gò Vấp', 2000000, 3500, 90000, 2000000, 'Wifi miễn phí, sân phơi đồ chung, giờ giấc tự do', 'Giá hợp lý, gần khu công nghiệp, hơi ồn vào giờ cao điểm'),
	(3, 'Phòng trọ khu Thủ Đức', 2800000, 4000, 120000, 2800000, 'Có máy giặt chung, bảo vệ 24/24, gần siêu thị mini', 'An ninh tốt, giá hơi cao nhưng tiện nghi đầy đủ');

-- Dumping structure for table mo_phong_songthu.khao_sat
CREATE TABLE IF NOT EXISTS `khao_sat` (
  `id_khao_sat` int NOT NULL AUTO_INCREMENT,
  `id_ai_hoi` int NOT NULL,
  `lua_chon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phuong_tien` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `diem_cuoi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cau_hoi_tiep_theo` int DEFAULT NULL,
  PRIMARY KEY (`id_khao_sat`),
  KEY `id_ai_hoi` (`id_ai_hoi`),
  KEY `id_cau_hoi_tiep_theo` (`id_cau_hoi_tiep_theo`),
  CONSTRAINT `khao_sat_ibfk_1` FOREIGN KEY (`id_ai_hoi`) REFERENCES `ai_hoi` (`id_ai_hoi`) ON DELETE CASCADE,
  CONSTRAINT `khao_sat_ibfk_2` FOREIGN KEY (`id_cau_hoi_tiep_theo`) REFERENCES `ai_hoi` (`id_ai_hoi`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mo_phong_songthu.khao_sat: ~3 rows (approximately)
INSERT INTO `khao_sat` (`id_khao_sat`, `id_ai_hoi`, `lua_chon`, `phuong_tien`, `mo_ta`, `diem_cuoi`, `id_cau_hoi_tiep_theo`) VALUES
	(2, 1, 'Đi học', 'Xe máy', '\nHôm nay trời mưa nên thời gian đi sẽ trễ 10 phút.\nThời gian dự kiến mất khoảng 20 phút.\nĐi từ trọ khoảng 2km nữa thì có chợ (chỗ này hay tắc đường kẹt xe).\nĐi tiếp 5km nữa thì có công an và đèn giao thông.\nĐi tiếp nữa và rẽ phải sẽ đến trường.', 'Vào trường và lên lớp học code', 6),
	(6, 6, 'Tôi muốn đi ăn', 'Xe máy', 'Người thuê trọ lấy xe máy ra quán ăn quen thuộc gần khu trọ.\nĐường phố lên đèn, không khí buổi tối mát mẻ hơn ban ngày.\nThời gian di chuyển dự kiến khoảng 12 phút.\nQuán khá đông vì là giờ cao điểm ăn tối.', 'Ngồi ăn tối cùng vài người bạn ở trọ', 3),
	(8, 3, 'Đi tập gym', 'Xe máy', 'Người thuê trọ lấy xe máy đến phòng gym gần khu trọ.\nThời gian di chuyển khoảng 10 phút.\nTập luyện khoảng 1 tiếng với các bài cơ bản.\nSau khi tập xong cảm thấy tỉnh táo và có năng lượng hơn.', 'Về trọ tắm rửa sau buổi tập', NULL);

-- Dumping structure for table mo_phong_songthu.nguoi_dung
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `ten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tuoi` int DEFAULT NULL,
  `gioi_tinh` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doi_tuong` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mo_phong_songthu.nguoi_dung: ~1 rows (approximately)
INSERT INTO `nguoi_dung` (`id_user`, `ten`, `tuoi`, `gioi_tinh`, `doi_tuong`) VALUES
	(1, 'Nguyen Van A', 20, 'Nam', 'Sinh vien');

-- Dumping structure for table mo_phong_songthu.video
CREATE TABLE IF NOT EXISTS `video` (
  `id_video` int NOT NULL AUTO_INCREMENT,
  `id_khao_sat` int NOT NULL,
  `ten_video` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duong_dan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_video`),
  KEY `id_khao_sat` (`id_khao_sat`),
  CONSTRAINT `video_ibfk_1` FOREIGN KEY (`id_khao_sat`) REFERENCES `khao_sat` (`id_khao_sat`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mo_phong_songthu.video: ~3 rows (approximately)
INSERT INTO `video` (`id_video`, `id_khao_sat`, `ten_video`, `duong_dan`) VALUES
	(1, 6, 'Hành trình đi học\r\n', 'videos/di_hoc_2.mp4'),
	(2, 2, 'Hành trình đi học', 'videos/di_hoc.mp4'),
	(3, 8, 'Hành trình đi học', 'videos/di_hoc_3.mp4');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
