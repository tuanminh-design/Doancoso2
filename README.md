# DANANG PC - WEBSITE THƯƠNG MẠI ĐIỆN TỬ LINH KIỆN & PHỤ KIỆN MÁY TÍNH

Website bán lẻ linh kiện máy tính, PC Gaming và phụ kiện máy tính chính hãng tại Đà Nẵng, phát triển thuần bằng **PHP 8 (PDO Prepared Statements)** và **MySQL**, tuân thủ kiến trúc phân tầng Layered/MVC rõ ràng, giao diện hiện đại, chuẩn Responsive và tích hợp đầy đủ quy trình bán hàng thương mại điện tử.

---

## 1. Điểm Nổi Bật & Tính Năng Dự Án

### 1.1. Khách Hàng (Storefront)
- **Trang chủ hiện đại**:
  - Banner giới thiệu thương hiệu DaNang PC với cam kết hàng chính hãng, giao hỏa tốc nội thành Đà Nẵng.
  - Lưới danh mục 14 nhóm linh kiện máy tính & phụ kiện (CPU, Mainboard, RAM, VGA, SSD, Nguồn, Case, Tản nhiệt, Màn hình, Bàn phím cơ, Chuột, Tai nghe, Loa, Phụ kiện).
  - 4 khu vực sản phẩm động từ MySQL: **Sản phẩm nổi bật**, **Khuyến mãi giảm giá sốc**, **Sản phẩm bán chạy nhất**, **Linh kiện mới về**.
  - Thẻ sản phẩm đầy đủ: Ảnh, Tên, Mã SKU, Giá bán, Giá gốc, Tỷ lệ giảm giá (-%), Nhãn Còn hàng / Hết hàng, Nút Chi tiết, Nút Thêm vào giỏ.
  - **Chuyên mục Tin tức công nghệ**: Tin thị trường phần cứng, cẩm nang build PC, phân tích linh kiện.
- **Danh mục sản phẩm & Tìm kiếm nâng cao**:
  - Lọc theo danh mục, lọc theo hãng sản xuất (Intel, AMD, ASUS, MSI, Gigabyte, Corsair...).
  - Lọc theo tình trạng còn hàng / hết hàng, lọc theo khoảng giá từ - đến.
  - Sắp xếp theo giá tăng/giảm dần, mới nhất, tên A-Z.
  - Phân trang tự động cho danh sách sản phẩm.
- **Trang chi tiết sản phẩm**:
  - Bảng thông số kỹ thuật (Specs) chi tiết: Socket, TDP, Bus RAM, VRAM, tốc độ đọc ghi...
  - Bộ chọn số lượng (tự động khóa không cho vượt quá số lượng tồn kho thực tế trong MySQL).
  - Khối sản phẩm liên quan cùng danh mục.
- **Giỏ hàng & Đặt hàng an toàn**:
  - Thêm, sửa số lượng, xóa linh kiện với thông báo Toast tương tác.
  - Kiểm tra tồn kho và đơn giá trực tiếp trên máy chủ (chống thao túng giá từ trình duyệt).
  - Quy trình thanh toán (Checkout) đầy đủ thông tin người nhận tại Đà Nẵng/toàn quốc.
  - Hỗ trợ 2 phương thức: **COD (Thanh toán khi nhận hàng)** và **Chuyển khoản ngân hàng** (hiển thị thông tin Vietcombank, STK, hướng dẫn chuyển tiền đối soát theo mã đơn hàng).
  - Giao dịch MySQL Transaction bảo vệ dữ liệu, tự động trừ tồn kho khi đặt hàng thành công.
- **Tài khoản người dùng**:
  - Đăng ký, đăng nhập bảo mật (mã hóa mật khẩu bằng `password_hash` Bcrypt).
  - Chống Session Fixation (tái tạo Session ID sau đăng nhập), mã xác thực CSRF Token trên mọi Form.
  - Trang cá nhân: cập nhật họ tên, SĐT, địa chỉ, đổi mật khẩu.
  - **Lịch sử đơn hàng**: theo dõi tiến trình đơn hàng (Chờ xác nhận, Đã xác nhận, Đang giao, Hoàn thành, Đã hủy).
- **Trang Chính sách & Liên hệ**:
  - Chính sách bảo hành 36 tháng chính hãng, chính sách vận chuyển hỏa tốc tại Đà Nẵng, chính sách đổi trả 1 đổi 1 trong 30 ngày, chính sách bảo mật thông tin.
  - Form liên hệ gửi phản hồi về hệ thống quản trị.

### 1.2. Phân Hệ Quản Trị Viên (Admin Dashboard)
- **Bảo mật truy cập**: Middleware kiểm tra quyền hạn server-side `role === 'admin'`.
- **Bảng điều khiển tổng quan (Dashboard)**:
  - Thống kê doanh thu thực tế từ MySQL, tổng số đơn hàng, đơn chờ xác nhận, tổng số sản phẩm, tổng khách hàng.
  - Bảng đơn hàng mới nhất và các lối tắt tác vụ.
- **Quản lý sản phẩm**:
  - Thêm mới, chỉnh sửa, xóa sản phẩm.
  - Upload ảnh sản phẩm an toàn (kiểm tra MIME type, kích thước tối đa 5MB, đổi tên file ngẫu nhiên, chặn thực thi mã độc).
  - Quản lý thông số kỹ thuật động (thêm/xóa dòng thông số linh hoạt).
- **Quản lý danh mục & Thương hiệu**:
  - CRUD danh mục và hãng sản xuất.
  - Cơ chế bảo vệ toàn vẹn: Không cho phép xóa danh mục/thương hiệu đang có sản phẩm kinh doanh.
- **Quản lý đơn hàng**:
  - Tìm kiếm theo mã đơn, khách hàng, lọc theo trạng thái.
  - Xem chi tiết từng đơn hàng, danh sách linh kiện đã mua.
  - Cập nhật trạng thái xử lý và trạng thái thanh toán.
  - **Hủy đơn hàng an toàn**: Tự động hoàn trả đúng số lượng linh kiện vào kho tồn MySQL và lưu lịch sử.
- **Quản lý người dùng**:
  - Xem danh sách khách hàng, tìm kiếm theo tên, SĐT, email.
  - Khóa (Blocked) hoặc Kích hoạt (Active) tài khoản khách.
  - Phân quyền Quản trị viên (Admin) / Khách hàng (Customer).
- **Quản lý tin tức công nghệ**:
  - Viết bài, upload ảnh đại diện, biên tập nội dung, chọn chuyên mục, bật/tắt xuất bản.
- **Cấu hình website & Ngân hàng**:
  - Chỉnh sửa tên showroom, hotline, email, địa chỉ tại Đà Nẵng, thời gian mở cửa.
  - Chỉnh sửa thông tin tài khoản ngân hàng chuyển khoản.
  - Chỉnh sửa trực tiếp nội dung 4 chính sách dịch vụ.

---

## 2. Cấu Trúc Thư Mục Dự Án

```
d:\Doancoso\
├── app/
│   ├── bootstrap.php            # Nạp autoload PSR-4, cấu hình timezone, session
│   ├── Router.php               # Bộ định tuyến URL thân thiện & tham số fallback
│   ├── Controllers/             # Xử lý logic tầng nghiệp vụ
│   │   ├── Controller.php       # Controller cơ sở, render view, CSRF, global data
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── AuthController.php
│   │   ├── AccountController.php
│   │   ├── NewsController.php
│   │   ├── PageController.php
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── ProductController.php
│   │       ├── CategoryController.php
│   │       ├── BrandController.php
│   │       ├── OrderController.php
│   │       ├── UserController.php
│   │       ├── NewsController.php
│   │       └── SettingController.php
│   ├── Middleware/              # Kiểm soát quyền truy cập
│   │   ├── AuthMiddleware.php
│   │   └── AdminMiddleware.php
│   ├── Models/                  # Tầng truy xuất CSDL qua PDO Prepared Statements
│   │   ├── Model.php            # Base PDO Model với Transaction support
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Brand.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── News.php
│   │   ├── Setting.php
│   │   └── ContactMessage.php
│   ├── Services/
│   │   └── CartService.php      # Quản lý giỏ hàng, xác thực kho & giá từ CSDL
│   └── Helpers/                 # Các tiện ích hệ thống
│       ├── Database.php         # Singleton kết nối PDO MySQL
│       ├── Session.php          # Session, Flash message, CSRF Token
│       ├── Auth.php             # Trạng thái đăng nhập & phân quyền
│       ├── Format.php           # Định dạng tiền tệ VNĐ, slug tiếng Việt, ngày tháng
│       ├── Url.php              # Tự động nhận diện Base URL, asset & uploads
│       └── Validator.php        # Kiểm tra tính hợp lệ dữ liệu máy chủ
├── config/
│   ├── database.php             # Cấu hình CSDL MySQL
│   └── app.php                  # Cấu hình website & upload
├── database/
│   └── pc_store.sql             # Toàn bộ cấu trúc bảng và dữ liệu mẫu phong phú
├── public/
│   ├── index.php                # Điểm vào ứng dụng (Front Controller)
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css         # Giao diện khách hàng hiện đại, responsive
│   │   │   └── admin.css        # Giao diện quản trị Admin trực quan
│   │   ├── js/
│   │   │   ├── main.js          # AJAX thêm giỏ hàng, toast notification
│   │   │   └── admin.js         # Quản lý specs động, preview ảnh
│   │   └── images/              # Logo, icon danh mục, ảnh SVG linh kiện mẫu
│   └── uploads/                 # Thư mục lưu ảnh upload từ admin
│       ├── products/
│       └── news/
├── views/                       # Tầng giao diện người dùng (Presentation)
│   ├── layouts/                 # Header, Footer, Admin layout
│   ├── home/
│   ├── products/
│   ├── cart/
│   ├── checkout/
│   ├── account/
│   ├── news/
│   ├── pages/
│   └── admin/
├── tests/                       # Bộ kiểm thử tự động
│   ├── test_flow.php            # Kiểm thử CSDL, giỏ hàng, giao dịch đơn hàng & hoàn kho
│   └── test_http.php            # Kiểm thử HTTP session & quyền Admin
├── index.php                    # Điểm vào gốc tương thích khi chạy htdocs XAMPP
├── .htaccess                    # URL rewrite & bảo vệ các file nhạy cảm
└── README.md
```

---

## 3. Hướng Dẫn Cài Đặt & Chạy Trên Localhost

### Yêu Cầu Môi Trường:
- PHP >= 8.1 (Có PDO MySQL extension).
- MySQL hoặc MariaDB >= 10.4.
- XAMPP, WAMP, Laragon hoặc built-in PHP server.

### Bước 1: Nhập Cơ Sở Dữ Liệu
1. Mở **phpMyAdmin** trên trình duyệt (thường là `http://localhost/phpmyadmin`).
2. Chọn tab **Import** (Nhập), nhấn **Choose File** và chọn file:
   ```
   d:/Doancoso/database/pc_store.sql
   ```
3. Nhấn **Import** (Thực hiện). File SQL sẽ tự động tạo cơ sở dữ liệu `pc_store` với đầy đủ 13 bảng, khóa ngoại, ràng buộc và 24 sản phẩm mẫu, danh mục, bài viết.

*(Hoặc dùng lệnh terminal nếu có MySQL CLI: `mysql -u root < database/pc_store.sql`)*.

### Bước 2: Cấu Hình Kết Nối Database (Nếu cần)
Mở file `config/database.php` và kiểm tra lại thông tin kết nối MySQL:
```php
return [
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'database' => 'pc_store',
    'username' => 'root',
    'password' => '', // Điền mật khẩu MySQL nếu máy bạn có đặt mật khẩu
];
```

### Bước 3: Chạy Website

#### Cách 1: Chạy 1-Click Bằng File `run_dev.bat` (Khuyên dùng - Nhanh nhất)
Chỉ cần nhấp đúp vào file `run_dev.bat` tại thư mục gốc dự án:
- File sẽ tự động kiểm tra và khởi động MySQL (nếu chưa chạy).
- Khởi chạy máy chủ web PHP tại cổng `8080`.
- Tự động mở trình duyệt truy cập ngay vào: 👉 **http://localhost:8080**

#### Cách 2: Sử Dụng PHP Built-in Server Thủ Công
Mở Terminal/PowerShell tại thư mục dự án và chạy:
```bash
php -S 127.0.0.1:8080 -t public
```
Sau đó mở trình duyệt truy cập:
👉 **http://localhost:8080**

#### Cách 3: Sử Dụng XAMPP Apache
Dự án đã được tạo liên kết thư mục (Junction) trực tiếp tới `C:\xampp\htdocs\Doancoso`:
1. Mở **XAMPP Control Panel**, nhấn **Start** cho cả **Apache** và **MySQL**.
2. Truy cập trực tiếp đường dẫn:
   👉 **http://localhost/Doancoso/** (hoặc **http://localhost:80/Doancoso/**)

---

## 4. Tài Khoản Đăng Nhập Kiểm Thử (Development Credentials)

> [!WARNING]
> Các tài khoản dưới đây được tạo sẵn trong cơ sở dữ liệu để phục vụ kiểm thử trong môi trường phát triển (Localhost). Khi đưa vào môi trường sản xuất thực tế, quản trị viên bắt buộc phải đổi mật khẩu ngay lập tức.

| Vai trò | Email đăng nhập | Mật khẩu | Quyền hạn |
| :--- | :--- | :--- | :--- |
| **Quản Trị Viên (Admin)** | `admin@danangpc.vn` | `Admin@123456` | Toàn quyền quản trị Dashboard, Sản phẩm, Đơn hàng, Kho, Khách hàng, Tin tức, Cấu hình |
| **Khách Hàng (Customer)** | `khachhang@gmail.com` | `Customer@123456` | Mua hàng, Giỏ hàng, Quản lý tài khoản, Xem lịch sử đơn hàng |

---

## 5. Chạy Kiểm Thử Tự Động (Automated Testing)

Dự án đã tích hợp sẵn kịch bản kiểm thử toàn diện để bạn có thể kiểm tra tính toàn vẹn của hệ thống bất kỳ lúc nào:

1. **Kiểm thử logic nghiệp vụ & kho hàng**:
   ```bash
   php tests/test_flow.php
   ```
   *Kiểm tra kết nối DB, xác thực mật khẩu bcrypt, truy vấn catalog, giỏ hàng, đặt hàng trong Transaction, trừ tồn kho, và khôi phục tồn kho khi hủy đơn.*

2. **Kiểm thử giao tiếp HTTP & phiên làm việc Admin**:
   ```bash
   php tests/test_http.php
   ```
   *Kiểm tra trích xuất CSRF Token, đăng nhập Admin bằng cURL cookie jar, và truy cập các trang quản trị được bảo vệ.*
