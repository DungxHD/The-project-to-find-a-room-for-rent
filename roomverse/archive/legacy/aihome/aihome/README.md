# AIHome — Trang chủ tìm phòng trọ (PHP MVC + mysqli + JS)

Giao diện trang chủ dựa trên ảnh mẫu bạn gửi, dựng theo mô hình MVC đơn giản
bằng PHP thuần (mysqli), có chức năng tìm kiếm AJAX trả về **4 phòng trọ
mới nhất & gần nhất** theo khoảng cách.

## 1. Cấu trúc thư mục

```
aihome/
├── config/
│   └── database.php          # Kết nối mysqli (sửa DB_HOST/DB_USER/DB_PASS/DB_NAME)
├── database/
│   └── schema.sql             # Toàn bộ CREATE TABLE + dữ liệu mẫu
├── models/                    # Model (M trong MVC)
│   ├── DoiTuongModel.php
│   ├── KhuVucModel.php
│   └── PhongTroModel.php      # Model tìm kiếm chính (Haversine + lọc)
├── controllers/                # Controller (C trong MVC)
│   └── SearchController.php
├── views/                      # View (V trong MVC)
│   └── partials/
│       ├── header.php
│       ├── footer.php
│       ├── hero_banner.php
│       └── room_card.php
├── api/
│   └── search.php              # Endpoint JSON gọi bằng fetch() từ JS
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/                 # ảnh hero + ảnh phòng (placeholder có sẵn)
└── index.php                   # Trang chủ, ghép toàn bộ view lại
```

## 2. Cài đặt

1. Tạo CSDL và import dữ liệu mẫu:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Mở `config/database.php`, chỉnh `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`
   cho đúng với MySQL/MariaDB của bạn.
3. Chạy bằng PHP built-in server (hoặc Apache/Nginx trỏ vào thư mục này):
   ```bash
   php -S localhost:8000
   ```
4. Mở trình duyệt tại `http://localhost:8000`.

Toàn bộ luồng trên (khởi tạo DB, import schema, chạy `php -S`, gọi
`index.php` và `api/search.php`) đã được kiểm thử thực tế trong quá trình
xây dựng — cả 2 đều trả về HTTP 200 và dữ liệu JSON đúng định dạng.

## 3. Vài quyết định thiết kế cần bạn biết

- **2 cột bổ sung nhỏ ngoài danh sách bảng bạn gửi** (đã ghi chú rõ trong
  `database/schema.sql`):
  - `phong_tro.created_at`: cần có để sắp xếp "phòng trọ mới nhất".
  - Bảng `danh_gia` (id, id_phong, id_tai_khoan, so_sao, noi_dung,
    created_at): để hiển thị số sao/đánh giá như trong ảnh mẫu (⭐ 4.7 (32)).
    Nếu bạn không cần đánh giá, có thể xoá bảng này — card sẽ tự hiển thị
    "Chưa có đánh giá" nếu không có dữ liệu.
- **"Khu vực" trong ô tìm kiếm** dùng lại chính bảng `toa_do`, lọc theo
  `id_loai = 1` (khu vực/quận huyện) — khác với `id_loai = 2` là toạ độ
  thật của từng phòng trọ (`phong_tro.id_toa_do`). Cách này tận dụng đúng
  bảng `loai_dia_diem` + `toa_do` bạn đã thiết kế mà không cần thêm bảng mới.
- **Hero banner chỉ lưu ảnh** đúng như yêu cầu — không có tiêu đề/nội dung
  lưu trong DB, chỉ là một mảng đường dẫn ảnh (`$heroImages` trong
  `index.php`) hiển thị dạng carousel đơn giản.

## 4. Cách tính "4 phòng mới nhất & gần nhất"

Trong `models/PhongTroModel.php`:

1. Xác định **điểm tham chiếu** (lat/lng): ưu tiên vị trí trình duyệt gửi
   lên (nút "dùng vị trí của tôi", dùng Geolocation API trong `main.js`),
   nếu không có thì dùng toạ độ của "Khu vực" người dùng chọn trong dropdown.
2. Nếu có điểm tham chiếu: tính khoảng cách bằng công thức **Haversine**
   ngay trong câu SQL, lọc theo bán kính đã chọn ("Khoảng cách"), sắp xếp
   **gần nhất trước, mới nhất làm tiêu chí phụ**.
3. Nếu không có điểm tham chiếu nào: chỉ sắp xếp theo `created_at DESC`.
4. Luôn giới hạn `LIMIT 4`.

Mỗi lần người dùng đổi lựa chọn trong form và bấm "Tìm kiếm" (hoặc bấm
"dùng vị trí của tôi"), `assets/js/main.js` gọi `api/search.php` bằng
`fetch()` và vẽ lại 4 card kết quả — không cần tải lại trang.

## 5. Việc cần làm khi đưa vào thực tế

- Thay ảnh placeholder trong `assets/images/` bằng ảnh thật.
- Thêm xác thực đăng nhập thật cho nút "Đăng nhập" (hiện chỉ là link `#`).
- Thêm phân trang / trang "Xem tất cả" cho danh sách đầy đủ phòng trọ.
- Cân nhắc thêm index cho `toa_do(latitude, longitude)` và
  `phong_tro(id_toa_do, trang_thai)` nếu dữ liệu lớn, vì Haversine hiện
  tính trên toàn bộ bảng rồi mới lọc bằng `HAVING`.
