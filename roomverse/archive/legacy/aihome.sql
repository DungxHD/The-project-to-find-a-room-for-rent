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


-- Dumping database structure for aihome
CREATE DATABASE IF NOT EXISTS `aihome` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `aihome`;

-- Dumping structure for table aihome.chu_tro
CREATE TABLE IF NOT EXISTS `chu_tro` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.chu_tro: ~1 rows (approximately)
INSERT INTO `chu_tro` (`id`, `ho_va_ten`, `email`, `cccd`, `ngay_sinh`, `dia_chi`, `avatar`, `so_dien_thoai`, `so_tai_khoan_ngan_hang`, `id_tai_khoan`) VALUES
	(1, 'Nguyễn Văn A', 'chutro1@example.com', '001099001234', '1990-05-12', 'Cầu Giấy, Hà Nội', 'avatar1.jpg', '0900000001', '0011001234567', 1);

-- Dumping structure for table aihome.danh_gia
CREATE TABLE IF NOT EXISTS `danh_gia` (
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.danh_gia: ~8 rows (approximately)
INSERT INTO `danh_gia` (`id`, `id_phong`, `id_tai_khoan`, `so_sao`, `noi_dung`, `created_at`) VALUES
	(1, 1, NULL, 5, 'Phòng đẹp, chủ nhiệt tình', '2026-07-08 13:59:40'),
	(2, 1, NULL, 4, 'Ổn', '2026-07-08 13:59:40'),
	(3, 1, NULL, 5, 'Tốt', '2026-07-08 13:59:40'),
	(4, 2, NULL, 5, 'Rất hài lòng', '2026-07-08 13:59:40'),
	(5, 2, NULL, 4, 'Giá hợp lý', '2026-07-08 13:59:40'),
	(6, 3, NULL, 4, 'Gần trường, tiện đi lại', '2026-07-08 13:59:40'),
	(7, 4, NULL, 5, 'View đẹp', '2026-07-08 13:59:40'),
	(8, 4, NULL, 5, 'Đáng tiền', '2026-07-08 13:59:40');

-- Dumping structure for table aihome.doi_tuong
CREATE TABLE IF NOT EXISTS `doi_tuong` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_doi_tuong` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.doi_tuong: ~2 rows (approximately)
INSERT INTO `doi_tuong` (`id`, `ten_doi_tuong`) VALUES
	(1, 'Sinh viên'),
	(2, 'Người đi làm');

-- Dumping structure for table aihome.hinh_anh
CREATE TABLE IF NOT EXISTS `hinh_anh` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_phong` int NOT NULL,
  `duong_dan` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_phong` (`id_phong`),
  CONSTRAINT `hinh_anh_ibfk_1` FOREIGN KEY (`id_phong`) REFERENCES `phong_tro` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.hinh_anh: ~4 rows (approximately)
INSERT INTO `hinh_anh` (`id`, `id_phong`, `duong_dan`) VALUES
	(1, 1, 'assets/images/rooms/room1.jpg'),
	(2, 2, 'assets/images/rooms/room2.jpg'),
	(3, 3, 'assets/images/rooms/room3.jpg'),
	(4, 4, 'assets/images/rooms/room4.jpg');

-- Dumping structure for table aihome.loai_dia_diem
CREATE TABLE IF NOT EXISTS `loai_dia_diem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_loai` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.loai_dia_diem: ~2 rows (approximately)
INSERT INTO `loai_dia_diem` (`id`, `ten_loai`) VALUES
	(1, 'Khu vực'),
	(2, 'Vị trí phòng trọ');

-- Dumping structure for table aihome.phong_tro
CREATE TABLE IF NOT EXISTS `phong_tro` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.phong_tro: ~4 rows (approximately)
INSERT INTO `phong_tro` (`id`, `ten_phong`, `so`, `gia`, `id_toa_do`, `dien_tich`, `tien_coc`, `so_nguoi_toi_da`, `trang_thai`, `id_doi_tuong`, `gioi_tinh`, `mo_ta`, `id_tai_khoan`, `created_at`) VALUES
	(1, 'Phòng trọ gần ĐH Quốc Gia', 'P101', 2800000, 5, 20.00, 2800000, 2, 'con_trong', 1, 'tat_ca', 'Phòng gác lửng gần ĐH Quốc Gia, đầy đủ nội thất.', 1, '2026-07-07 13:59:39'),
	(2, 'Phòng trọ của Hà An', 'P202', 1200000, 6, 22.00, 1200000, 2, 'con_trong', 1, 'tat_ca', 'Phòng thoáng mát ,gần cao đẳng fpt', 1, '2026-07-06 13:59:39'),
	(3, 'Phòng trọ gần ĐH Sư Phạm', 'P303', 2600000, 7, 18.00, 2600000, 1, 'con_trong', 1, 'tat_ca', 'Phòng gác lửng có sofa, gần ĐH Sư Phạm.', 1, '2026-07-05 13:59:39'),
	(4, 'Phòng trọ Kim Mã Thượng', 'P404', 3000000, 8, 25.00, 3000000, 2, 'con_trong', 2, 'tat_ca', 'Phòng rộng rãi, ban công thoáng, gần Kim Mã Thượng.', 1, '2026-07-04 13:59:39');

-- Dumping structure for table aihome.phong_vr_360
CREATE TABLE IF NOT EXISTS `phong_vr_360` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_phong` int NOT NULL,
  `ten_goc_nhin` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duong_dan_anh` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_phong` (`id_phong`),
  CONSTRAINT `fk_vr_phong` FOREIGN KEY (`id_phong`) REFERENCES `phong_tro` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.phong_vr_360: ~0 rows (approximately)

-- Dumping structure for table aihome.tai_khoan
CREATE TABLE IF NOT EXISTS `tai_khoan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ten_dang_nhap` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vai_tro` enum('admin','chu_tro','nguoi_dung') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chu_tro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.tai_khoan: ~1 rows (approximately)
INSERT INTO `tai_khoan` (`id`, `ho_ten`, `sdt`, `email`, `ten_dang_nhap`, `mat_khau`, `vai_tro`) VALUES
	(1, 'Nguyễn Văn A', '0900000001', 'chutro1@example.com', 'chutro1', '$2y$10$examplehashexamplehashexamplehas', 'chu_tro');

-- Dumping structure for table aihome.tien_ich
CREATE TABLE IF NOT EXISTS `tien_ich` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.tien_ich: ~4 rows (approximately)
INSERT INTO `tien_ich` (`id_tien_ich`, `id_phong`, `wifi`, `dieu_hoa`, `may_lanh`, `may_giat`, `may_say`, `tu_lanh`, `giuong`, `tu_quan_ao`, `ban_hoc`, `ghe`, `rem_cua`, `ke_bep`, `may_nuoc_nong`) VALUES
	(1, 1, 1, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 0, 1),
	(2, 2, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 1),
	(3, 3, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1),
	(4, 4, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- Dumping structure for table aihome.toa_do
CREATE TABLE IF NOT EXISTS `toa_do` (
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table aihome.toa_do: ~8 rows (approximately)
INSERT INTO `toa_do` (`id`, `ten_dia_diem`, `id_loai`, `latitude`, `longitude`, `link_google`, `dia_chi`) VALUES
	(1, 'FPT Polytechnic Thái Nguyên', 1, 21.5957090, 105.8118490, 'https://maps.app.goo.gl/xDY3doEd7x2t3H7V7', 'Đường đê Mỏ Bạch, tổ 10 phường, Quyết Thắng, Thái Nguyên'),
	(2, 'Quận Đống Đa, Hà Nội', 1, 21.0136000, 105.8248000, 'https://maps.google.com/?q=Dong+Da+Ha+Noi', 'Đống Đa, Hà Nội'),
	(3, 'Quận Ba Đình, Hà Nội', 1, 21.0350000, 105.8140000, 'https://maps.google.com/?q=Ba+Dinh+Ha+Noi', 'Ba Đình, Hà Nội'),
	(4, 'Quận Thanh Xuân, Hà Nội', 1, 20.9955000, 105.8060000, 'https://maps.google.com/?q=Thanh+Xuan+Ha+Noi', 'Thanh Xuân, Hà Nội'),
	(5, 'Đường Xuân Thủy, Cầu Giấy, Hà Nội', 2, 21.0369000, 105.7830000, 'https://maps.google.com/?q=21.0369,105.7830', 'Đường Xuân Thủy, Cầu Giấy, Hà Nội'),
	(6, 'Phong tro Ha An', 2, 21.5954870, 105.8135940, 'https://maps.app.goo.gl/1ZAkVTDoqr4Ecfuo9', 'Phòng trọ của Hà, Phan Đình Phùng, Thái Nguyên'),
	(7, 'Chùa Láng, Đống Đa, Hà Nội', 2, 21.0231000, 105.8138000, 'https://maps.google.com/?q=21.0231,105.8138', 'Chùa Láng, Đống Đa, Hà Nội'),
	(8, 'Kim Mã Thượng, Ba Đình, Hà Nội', 2, 21.0378000, 105.8188000, 'https://maps.google.com/?q=21.0378,105.8188', 'Kim Mã Thượng, Ba Đình, Hà Nội');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
