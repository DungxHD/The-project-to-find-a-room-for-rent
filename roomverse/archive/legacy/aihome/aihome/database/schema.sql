-- =====================================================================
-- AIHome - Schema CSDL
-- Dựa trên các bảng do bạn cung cấp. Có 2 bổ sung nhỏ được đánh dấu rõ:
--   1) phong_tro.created_at  -> để sắp xếp "phòng trọ mới nhất"
--   2) bảng danh_gia         -> để hiển thị số sao/đánh giá như trong hình mẫu
-- Nếu bạn không cần đánh giá sao, có thể bỏ qua bảng danh_gia và phần
-- code liên quan trong models/PhongTroModel.php (đã ghi chú rõ).
-- =====================================================================

SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS aihome CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aihome;

-- ---------------------------------------------------------------------
-- 1. loai_dia_diem
-- id_loai = 1  ->  "Khu vực"        (dùng để chọn KHU VỰC TÌM KIẾM: quận/huyện)
-- id_loai = 2  ->  "Vị trí phòng"   (toạ độ thật của từng phòng trọ)
-- ---------------------------------------------------------------------
CREATE TABLE loai_dia_diem (
    id        INT PRIMARY KEY AUTO_INCREMENT,
    ten_loai  VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

INSERT INTO loai_dia_diem (id, ten_loai) VALUES
(1, 'Khu vực'),
(2, 'Vị trí phòng trọ');

-- ---------------------------------------------------------------------
-- 2. toa_do
-- ---------------------------------------------------------------------
CREATE TABLE toa_do (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    ten_dia_diem  VARCHAR(255) NOT NULL,
    id_loai       INT NOT NULL,
    latitude      DECIMAL(10,7) NOT NULL,
    longitude     DECIMAL(10,7) NOT NULL,
    link_google   VARCHAR(500),
    dia_chi       VARCHAR(255),
    FOREIGN KEY (id_loai) REFERENCES loai_dia_diem(id)
) ENGINE=InnoDB;

-- Các khu vực dùng cho ô tìm kiếm "Khu vực" (id_loai = 1)
INSERT INTO toa_do (ten_dia_diem, id_loai, latitude, longitude, link_google, dia_chi) VALUES
('Quận Cầu Giấy, Hà Nội',  1, 21.0362, 105.7920, 'https://maps.google.com/?q=Cau+Giay+Ha+Noi',  'Cầu Giấy, Hà Nội'),
('Quận Đống Đa, Hà Nội',   1, 21.0136, 105.8248, 'https://maps.google.com/?q=Dong+Da+Ha+Noi',   'Đống Đa, Hà Nội'),
('Quận Ba Đình, Hà Nội',   1, 21.0350, 105.8140, 'https://maps.google.com/?q=Ba+Dinh+Ha+Noi',   'Ba Đình, Hà Nội'),
('Quận Thanh Xuân, Hà Nội',1, 20.9955, 105.8060, 'https://maps.google.com/?q=Thanh+Xuan+Ha+Noi','Thanh Xuân, Hà Nội');

-- Vị trí thực tế của từng phòng trọ (id_loai = 2)
INSERT INTO toa_do (ten_dia_diem, id_loai, latitude, longitude, link_google, dia_chi) VALUES
('Đường Xuân Thủy, Cầu Giấy, Hà Nội', 2, 21.0369, 105.7830, 'https://maps.google.com/?q=21.0369,105.7830', 'Đường Xuân Thủy, Cầu Giấy, Hà Nội'),
('Duy Tân, Cầu Giấy, Hà Nội',         2, 21.0308, 105.7825, 'https://maps.google.com/?q=21.0308,105.7825', 'Duy Tân, Cầu Giấy, Hà Nội'),
('Chùa Láng, Đống Đa, Hà Nội',        2, 21.0231, 105.8138, 'https://maps.google.com/?q=21.0231,105.8138', 'Chùa Láng, Đống Đa, Hà Nội'),
('Kim Mã Thượng, Ba Đình, Hà Nội',    2, 21.0378, 105.8188, 'https://maps.google.com/?q=21.0378,105.8188', 'Kim Mã Thượng, Ba Đình, Hà Nội');

-- ---------------------------------------------------------------------
-- 3. doi_tuong  ("Tìm cho": Sinh viên / Người đi làm)
-- ---------------------------------------------------------------------
CREATE TABLE doi_tuong (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    ten_doi_tuong  VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

INSERT INTO doi_tuong (id, ten_doi_tuong) VALUES
(1, 'Sinh viên'),
(2, 'Người đi làm');

-- ---------------------------------------------------------------------
-- 4. tai_khoan
-- ---------------------------------------------------------------------
CREATE TABLE tai_khoan (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    ho_ten         VARCHAR(150),
    sdt            VARCHAR(20),
    email          VARCHAR(150),
    ten_dang_nhap  VARCHAR(100) UNIQUE,
    mat_khau       VARCHAR(255),
    vai_tro        ENUM('admin','chu_tro','nguoi_dung') NOT NULL DEFAULT 'chu_tro'
) ENGINE=InnoDB;

INSERT INTO tai_khoan (ho_ten, sdt, email, ten_dang_nhap, mat_khau, vai_tro) VALUES
('Nguyễn Văn A', '0900000001', 'chutro1@example.com', 'chutro1', '$2y$10$examplehashexamplehashexamplehas', 'chu_tro');

-- ---------------------------------------------------------------------
-- 5. chu_tro
-- ---------------------------------------------------------------------
CREATE TABLE chu_tro (
    id                       INT PRIMARY KEY AUTO_INCREMENT,
    ho_va_ten                VARCHAR(150),
    email                    VARCHAR(150),
    cccd                     VARCHAR(20),
    ngay_sinh                DATE,
    dia_chi                  VARCHAR(255),
    avatar                   VARCHAR(255),
    so_dien_thoai            VARCHAR(20),
    so_tai_khoan_ngan_hang   VARCHAR(50),
    id_tai_khoan             INT,
    FOREIGN KEY (id_tai_khoan) REFERENCES tai_khoan(id)
) ENGINE=InnoDB;

INSERT INTO chu_tro (ho_va_ten, email, cccd, ngay_sinh, dia_chi, avatar, so_dien_thoai, so_tai_khoan_ngan_hang, id_tai_khoan) VALUES
('Nguyễn Văn A', 'chutro1@example.com', '001099001234', '1990-05-12', 'Cầu Giấy, Hà Nội', 'avatar1.jpg', '0900000001', '0011001234567', 1);

-- ---------------------------------------------------------------------
-- 6. phong_tro
-- (created_at là cột BỔ SUNG so với danh sách gốc, phục vụ sắp xếp "mới nhất")
-- ---------------------------------------------------------------------
CREATE TABLE phong_tro (
    id               INT PRIMARY KEY AUTO_INCREMENT,
    ten_phong        VARCHAR(255) NOT NULL,
    so               VARCHAR(20),
    gia              DECIMAL(12,0) NOT NULL,
    id_toa_do        INT NOT NULL,
    dien_tich        DECIMAL(6,2),
    tien_coc         DECIMAL(12,0),
    so_nguoi_toi_da  INT,
    trang_thai       ENUM('con_trong','da_thue') NOT NULL DEFAULT 'con_trong',
    id_doi_tuong     INT,
    gioi_tinh        ENUM('nam','nu','tat_ca') NOT NULL DEFAULT 'tat_ca',
    mo_ta            TEXT,
    id_tai_khoan     INT,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- [BỔ SUNG]
    FOREIGN KEY (id_toa_do)    REFERENCES toa_do(id),
    FOREIGN KEY (id_doi_tuong) REFERENCES doi_tuong(id),
    FOREIGN KEY (id_tai_khoan) REFERENCES tai_khoan(id)
) ENGINE=InnoDB;

INSERT INTO phong_tro
(ten_phong, so, gia, id_toa_do, dien_tich, tien_coc, so_nguoi_toi_da, trang_thai, id_doi_tuong, gioi_tinh, mo_ta, id_tai_khoan, created_at) VALUES
('Phòng trọ gần ĐH Quốc Gia', 'P101', 2800000, 5, 20.0, 2800000, 2, 'con_trong', 1, 'tat_ca', 'Phòng gác lửng gần ĐH Quốc Gia, đầy đủ nội thất.', 1, NOW() - INTERVAL 1 DAY),
('Phòng trọ phố Duy Tân',      'P202', 3200000, 6, 22.0, 3200000, 2, 'con_trong', 1, 'tat_ca', 'Phòng thoáng mát, gần phố Duy Tân.', 1, NOW() - INTERVAL 2 DAY),
('Phòng trọ gần ĐH Sư Phạm',   'P303', 2600000, 7, 18.0, 2600000, 1, 'con_trong', 1, 'tat_ca', 'Phòng gác lửng có sofa, gần ĐH Sư Phạm.', 1, NOW() - INTERVAL 3 DAY),
('Phòng trọ Kim Mã Thượng',    'P404', 3000000, 8, 25.0, 3000000, 2, 'con_trong', 2, 'tat_ca', 'Phòng rộng rãi, ban công thoáng, gần Kim Mã Thượng.', 1, NOW() - INTERVAL 4 DAY);

-- ---------------------------------------------------------------------
-- 7. hinh_anh
-- ---------------------------------------------------------------------
CREATE TABLE hinh_anh (
    id        INT PRIMARY KEY AUTO_INCREMENT,
    id_phong  INT NOT NULL,
    duong_dan VARCHAR(500) NOT NULL,
    FOREIGN KEY (id_phong) REFERENCES phong_tro(id)
) ENGINE=InnoDB;

INSERT INTO hinh_anh (id_phong, duong_dan) VALUES
(1, 'assets/images/rooms/room1.jpg'),
(2, 'assets/images/rooms/room2.jpg'),
(3, 'assets/images/rooms/room3.jpg'),
(4, 'assets/images/rooms/room4.jpg');

-- ---------------------------------------------------------------------
-- 8. tien_ich
-- ---------------------------------------------------------------------
CREATE TABLE tien_ich (
    id_tien_ich    INT PRIMARY KEY AUTO_INCREMENT,
    id_phong       INT NOT NULL,
    wifi           TINYINT(1) NOT NULL DEFAULT 0,
    dieu_hoa       TINYINT(1) NOT NULL DEFAULT 0,
    may_lanh       TINYINT(1) NOT NULL DEFAULT 0,
    may_giat       TINYINT(1) NOT NULL DEFAULT 0,
    may_say        TINYINT(1) NOT NULL DEFAULT 0,
    tu_lanh        TINYINT(1) NOT NULL DEFAULT 0,
    giuong         TINYINT(1) NOT NULL DEFAULT 1,
    tu_quan_ao     TINYINT(1) NOT NULL DEFAULT 0,
    ban_hoc        TINYINT(1) NOT NULL DEFAULT 0,
    ghe            TINYINT(1) NOT NULL DEFAULT 0,
    rem_cua        TINYINT(1) NOT NULL DEFAULT 0,
    ke_bep         TINYINT(1) NOT NULL DEFAULT 0,
    may_nuoc_nong  TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_phong) REFERENCES phong_tro(id)
) ENGINE=InnoDB;

INSERT INTO tien_ich (id_phong, wifi, dieu_hoa, may_lanh, may_giat, may_say, tu_lanh, giuong, tu_quan_ao, ban_hoc, ghe, rem_cua, ke_bep, may_nuoc_nong) VALUES
(1, 1, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 0, 1),
(2, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 1),
(3, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1),
(4, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- ---------------------------------------------------------------------
-- 9. danh_gia  [BỔ SUNG - phục vụ hiển thị số sao/đánh giá như hình mẫu]
-- Nếu không cần, có thể xoá bảng này và bỏ phần JOIN đánh giá trong
-- models/PhongTroModel.php
-- ---------------------------------------------------------------------
CREATE TABLE danh_gia (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    id_phong     INT NOT NULL,
    id_tai_khoan INT,
    so_sao       TINYINT NOT NULL CHECK (so_sao BETWEEN 1 AND 5),
    noi_dung     VARCHAR(500),
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_phong) REFERENCES phong_tro(id)
) ENGINE=InnoDB;

INSERT INTO danh_gia (id_phong, id_tai_khoan, so_sao, noi_dung) VALUES
(1, NULL, 5, 'Phòng đẹp, chủ nhiệt tình'), (1, NULL, 4, 'Ổn'), (1, NULL, 5, 'Tốt'),
(2, NULL, 5, 'Rất hài lòng'), (2, NULL, 4, 'Giá hợp lý'),
(3, NULL, 4, 'Gần trường, tiện đi lại'),
(4, NULL, 5, 'View đẹp'), (4, NULL, 5, 'Đáng tiền');
