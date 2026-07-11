# RoomVerse

RoomVerse là bản hợp nhất của 2 phần dự án cũ:

- `AIHome`: tìm phòng trọ thông minh
- `SongThu`: mô phỏng "sống thử trước khi thuê"

## Cấu trúc mới

```text
roomverse/
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── public/
│   ├── api/
│   ├── assets/
│   ├── index.php
│   └── simulation.php
├── database/
│   └── roomverse.sql
└── archive/
    └── legacy/
```

## Cấu trúc runtime

- `roomverse/` là thư mục deploy duy nhất sau khi gộp.
- `app/` chứa MVC runtime chính:
  - `Controllers/` điều phối dữ liệu và request.
  - `Models/` chứa truy vấn database đã hợp nhất.
  - `Views/` chứa giao diện cho trang chủ và module sống thử.
- `public/` là web root, chỉ chứa entry point, API và asset đã chuẩn hóa tên.
- `database/roomverse.sql` là file database duy nhất cần import.
- `archive/legacy/` chỉ để đối chiếu mã nguồn gốc của 2 dự án cũ, không tham gia runtime.

## Điểm đã gộp

- Gộp 2 thư mục dự án thành một codebase `roomverse`
- Gộp 2 database cũ thành 1 database `roomverse`
- Chuẩn hóa cấu trúc theo hướng MVC rõ ràng hơn
- Đổi tên các file chung chung như `db.php`, `api.php`, `style.css`, `app.js`
  sang vị trí/tên có ngữ nghĩa hơn
- Đổi tên model sống thử sang `LivingSimulationModel.php` để dễ đọc hơn sau khi hợp nhất
- Gắn dữ liệu đánh giá của module sống thử với đúng `room_id` trong database dùng chung
- Giữ nguyên logic nghiệp vụ chính của từng phần, chỉ chỉnh để thống nhất
  đường dẫn, tên bảng và luồng runtime

## Chạy dự án

1. Import file `database/roomverse.sql`
2. Chỉnh cấu hình trong `app/Config/Database.php` nếu cần
3. Chạy PHP built-in server tại thư mục `public`

```bash
cd roomverse/public
php -S localhost:8000
```

4. Mở:

- `http://localhost:8000/` cho trang tìm phòng
- `http://localhost:8000/simulation.php` cho module sống thử

## Ghi chú

- Bản gốc của 2 dự án cũ được giữ trong `archive/legacy/` để tiện đối chiếu khi cần
- Các comment `Da sua:` trong mã nguồn đánh dấu những chỗ tôi đã chỉnh để
  gộp, tránh xung đột hoặc cải thiện cấu trúc/bảo trì
- Khi cần mở rộng tiếp, hãy ưu tiên sửa trong `app/`, `public/`, `database/`; không sửa trực tiếp trong `archive/legacy/`
