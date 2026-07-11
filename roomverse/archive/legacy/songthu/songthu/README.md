# Mô phỏng: Một Ngày Sống Thử Ở Trọ

## Cấu trúc thư mục
```
songthu/
├── index.php        -> Giao diện chính
├── api.php          -> API xử lý câu hỏi / lựa chọn / phân tích (mysqli)
├── db.php           -> Kết nối database
├── css/style.css    -> Giao diện nền trắng, tông xanh nước biển
├── js/app.js        -> Điều khiển toàn bộ luồng mô phỏng
├── sql/schema.sql    -> Cấu trúc + dữ liệu mẫu 4 bảng
└── videos/           -> ĐẶT VIDEO CỦA BẠN VÀO ĐÂY
```

## Cài đặt (XAMPP / LAMP)
1. Copy thư mục `songthu` vào `htdocs` (XAMPP) hoặc `www`.
2. Mở phpMyAdmin (hoặc mysql CLI), chạy file `sql/schema.sql` để tạo database
   `mo_phong_songthu` cùng dữ liệu mẫu.
3. Mở `db.php`, chỉnh lại `$DB_USER`, `$DB_PASS` nếu MySQL của bạn khác mặc định.
4. Đặt các video minh họa của bạn vào thư mục `videos/` với đúng tên đã khai
   báo trong bảng `video` (ví dụ: `videos/di_hoc.mp4`, `videos/di_an.mp4`...).
   Bạn có thể đổi tên file, chỉ cần cập nhật lại cột `duong_dan` trong bảng `video`.
5. Truy cập `http://localhost/songthu/` để chạy mô phỏng.

## Cách hoạt động
- Bảng `ai_hoi`: mỗi dòng là một câu hỏi trong ngày (theo cột `stt` tăng dần:
  7:00 → 12:00 → ...). Muốn thêm mốc thời gian mới (VD 18:00 ăn tối, 22:00 đi
  ngủ), chỉ cần thêm dòng mới với `stt` kế tiếp.
- Bảng `khao_sat`: phân tích ứng với TỪNG lựa chọn của TỪNG câu hỏi (cột
  `lua_chon` phải khớp chính xác với `lua_chon_1/2/3` trong `ai_hoi`).
- Bảng `video`: video minh họa tương ứng với từng dòng `khao_sat`.
- Luồng JS (`js/app.js`):
  1. Gọi `api.php?action=get_question` để lấy câu hỏi + các lựa chọn.
  2. Người dùng bấm chọn -> gọi `api.php` (`action=submit_choice`) để lấy
     phân tích (`mo_ta`, `diem_cuoi`) và video tương ứng.
  3. Hiển thị phân tích theo dạng lộ trình (route timeline), từng dòng hiện
     dần lên.
  4. Sau khi hiện hết, màn hình mờ dần sang đen rồi phát video toàn màn hình.
  5. Video kết thúc -> tự động load câu hỏi kế tiếp (`stt + 1`).
  6. Hết dữ liệu (không còn `stt` tiếp theo) -> hiện màn hình kết thúc.

## Mở rộng thêm mốc thời gian mới
```sql
INSERT INTO ai_hoi (id_user, stt, thoi_gian_hien_tai, noi_dung_hoi, lua_chon_1, lua_chon_2, lua_chon_3)
VALUES (1, 3, '18:00', 'Bây giờ là 18:00, bạn muốn làm gì?', 'Đi ăn tối', 'Học bài', 'Đi ngủ sớm');

INSERT INTO khao_sat (id_ai_hoi, lua_chon, phuong_tien, mo_ta, diem_cuoi) VALUES
(3, 'Đi ăn tối', '...', '...\n...', '...');

INSERT INTO video (id_khao_sat, ten_video, duong_dan) VALUES
(<id_khao_sat_vua_tao>, 'Ten video', 'videos/an_toi.mp4');
```
