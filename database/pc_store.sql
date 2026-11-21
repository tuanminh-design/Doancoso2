-- ==============================================================================
-- DaNang PC - E-Commerce Database Schema & Seed Data
-- Database Name: pc_store
-- Storage Engine: InnoDB
-- Character Set: utf8mb4 / utf8mb4_unicode_ci
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `pc_store` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pc_store`;

-- Disable foreign key checks during schema creation and data population
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Table: users
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NULL,
  `password` VARCHAR(255) NOT NULL,
  `address` TEXT NULL,
  `role` ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
  `status` ENUM('active', 'blocked') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 2. Table: categories
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `icon` VARCHAR(50) DEFAULT 'fa-microchip',
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. Table: brands
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `logo` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. Table: products
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `brand_id` INT NULL,
  `name` VARCHAR(255) NOT NULL,
  `sku` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `price` DECIMAL(15,2) NOT NULL,
  `sale_price` DECIMAL(15,2) NULL,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `thumbnail` VARCHAR(255) NULL,
  `short_description` TEXT NULL,
  `description` LONGTEXT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `view_count` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  INDEX `idx_product_category` (`category_id`),
  INDEX `idx_product_brand` (`brand_id`),
  INDEX `idx_product_price` (`price`),
  INDEX `idx_product_featured` (`is_featured`),
  INDEX `idx_product_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. Table: product_images
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. Table: product_specs
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `product_specs`;
CREATE TABLE `product_specs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `spec_name` VARCHAR(100) NOT NULL,
  `spec_value` VARCHAR(255) NOT NULL,
  `sort_order` INT DEFAULT 0,
  CONSTRAINT `fk_specs_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. Table: orders
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_code` VARCHAR(32) NOT NULL UNIQUE,
  `user_id` INT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(191) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `notes` TEXT NULL,
  `payment_method` ENUM('cod', 'bank_transfer') NOT NULL DEFAULT 'cod',
  `payment_status` ENUM('unpaid', 'paid') NOT NULL DEFAULT 'unpaid',
  `status` ENUM('pending', 'confirmed', 'shipping', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` DECIMAL(15,2) NOT NULL,
  `shipping_fee` DECIMAL(15,2) DEFAULT 0.00,
  `total_amount` DECIMAL(15,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_order_status` (`status`),
  INDEX `idx_order_user` (`user_id`),
  INDEX `idx_order_code` (`order_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 8. Table: order_items
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `product_sku` VARCHAR(50) NOT NULL,
  `price` DECIMAL(15,2) NOT NULL,
  `quantity` INT NOT NULL,
  `total` DECIMAL(15,2) NOT NULL,
  CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 9. Table: order_status_history
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE `order_status_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `note` TEXT NULL,
  `created_by` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_history_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 10. Table: news_categories
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `news_categories`;
CREATE TABLE `news_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 11. Table: news
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NULL,
  `user_id` INT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `thumbnail` VARCHAR(255) NULL,
  `summary` TEXT NOT NULL,
  `content` LONGTEXT NOT NULL,
  `author_name` VARCHAR(100) DEFAULT 'Ban Biên Tập DaNang PC',
  `is_published` TINYINT(1) DEFAULT 1,
  `view_count` INT DEFAULT 0,
  `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_news_published` (`is_published`),
  INDEX `idx_news_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 12. Table: contact_messages
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(20) NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 13. Table: site_settings
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` LONGTEXT NULL,
  `group_name` VARCHAR(50) DEFAULT 'general',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ==============================================================================
-- SEED DATA
-- ==============================================================================

-- 1. Users
-- Default accounts:
-- Administrator: admin@danangpc.vn / Admin@123456
-- Customer: khachhang@gmail.com / Customer@123456
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `address`, `role`, `status`) VALUES
(1, 'Quản Trị Viên DaNang PC', 'admin@danangpc.vn', '0905123456', '$2y$10$87.sKOGBtkIBtgaJa9i/.e.hDnURnCoZVNfOwUmXPRGv4FNvCQDCi', '123 Nguyễn Văn Linh, P. Nam Dương, Q. Hải Châu, TP. Đà Nẵng', 'admin', 'active'),
(2, 'Nguyễn Văn An', 'khachhang@gmail.com', '0912345678', '$2y$10$9mFMqGvPBINdXosxUKxzSucpjqtemnGya3TMLdZaD943FiCMYT8s2', '45 Lê Duẩn, P. Thạch Thang, Q. Hải Châu, TP. Đà Nẵng', 'customer', 'active');

-- 2. Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `sort_order`, `is_active`) VALUES
(1, 'CPU - Bộ Vi Xử Lý', 'cpu-bo-vi-xu-ly', 'Bộ vi xử lý Intel Core i, AMD Ryzen thế hệ mới cho Gaming và Đồ Họa', 'fa-microchip', 1, 1),
(2, 'Mainboard - Bo Mạch Chủ', 'mainboard-bo-mach-chu', 'Bo mạch chủ Intel Z790, B760, AMD X670, B650 chính hãng', 'fa-chess-board', 2, 1),
(3, 'RAM - Bộ Nhớ Trong', 'ram-bo-nho-trong', 'RAM DDR4, DDR5 tản nhiệt LED RGB hiệu năng cao', 'fa-memory', 3, 1),
(4, 'VGA - Card Màn Hình', 'vga-card-man-hinh', 'Card đồ họa NVIDIA RTX 40 series, AMD Radeon RX 7000 series', 'fa-desktop', 4, 1),
(5, 'Ổ Cứng SSD & HDD', 'o-cung-ssd-hdd', 'Ổ cứng SSD NVMe PCIe Gen 4, Gen 5 và HDD lưu trữ tốc độ vượt trội', 'fa-hard-drive', 5, 1),
(6, 'Nguồn Máy Tính (PSU)', 'nguon-may-tinh-psu', 'Bộ nguồn công suất thực chuẩn 80 Plus Bronze, Gold, Platinum', 'fa-plug', 6, 1),
(7, 'Vỏ Case Máy Tính', 'vo-case-may-tinh', 'Vỏ máy tính kính cường lực, thiết kế thông gió tối ưu', 'fa-cube', 7, 1),
(8, 'Tản Nhiệt & Quạt Fan', 'tan-nhiet-quat-fan', 'Tản nhiệt nước AIO, tản khí tháp đôi và combo quạt ARGB', 'fa-fan', 8, 1),
(9, 'Màn Hình Máy Tính', 'man-hinh-may-tinh', 'Màn hình Gaming 144Hz - 240Hz, màn hình đồ họa IPS 2K/4K sắc nét', 'fa-tv', 9, 1),
(10, 'Bàn Phím Cơ', 'ban-phim-co', 'Bàn phím cơ Custom, Switch cơ học gõ êm, kết nối không dây', 'fa-keyboard', 10, 1),
(11, 'Chuột Gaming & Văn Phòng', 'chuot-gaming-van-phong', 'Chuột gaming cảm biến quang học cao cấp, thiết kế công thái học', 'fa-mouse', 11, 1),
(12, 'Tai Nghe Máy Tính', 'tai-nghe-may-tinh', 'Tai nghe Gaming âm thanh vòm 7.1, micro lọc ồn đàm thoại rõ ràng', 'fa-headphones', 12, 1),
(13, 'Loa Máy Tính', 'loa-may-tinh', 'Loa vi tính 2.0, 2.1 âm bass uy lực, kết nối Bluetooth tiện lợi', 'fa-volume-high', 13, 1),
(14, 'Phụ Kiện Máy Tính', 'phu-kien-may-tinh', 'Giá treo tai nghe, arm màn hình, keo tản nhiệt, lót chuột pad', 'fa-gamepad', 14, 1);

-- 3. Brands
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`) VALUES
(1, 'Intel', 'intel', 'brand_intel.png'),
(2, 'AMD', 'amd', 'brand_amd.png'),
(3, 'ASUS', 'asus', 'brand_asus.png'),
(4, 'MSI', 'msi', 'brand_msi.png'),
(5, 'GIGABYTE', 'gigabyte', 'brand_gigabyte.png'),
(6, 'Corsair', 'corsair', 'brand_corsair.png'),
(7, 'Kingston', 'kingston', 'brand_kingston.png'),
(8, 'Samsung', 'samsung', 'brand_samsung.png'),
(9, 'Seasonic', 'seasonic', 'brand_seasonic.png'),
(10, 'NZXT', 'nzxt', 'brand_nzxt.png'),
(11, 'Thermalright', 'thermalright', 'brand_thermalright.png'),
(12, 'LG', 'lg', 'brand_lg.png'),
(13, 'Logitech', 'logitech', 'brand_logitech.png'),
(14, 'Razer', 'razer', 'brand_razer.png'),
(15, 'DareU', 'dareu', 'brand_dareu.png');

-- 4. Products (24 realistic PC components & accessories)
INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `sku`, `slug`, `price`, `sale_price`, `stock_quantity`, `thumbnail`, `short_description`, `description`, `is_featured`, `is_active`, `view_count`) VALUES
(1, 1, 1, 'CPU Intel Core i5-14600K (Up to 5.3GHz, 14 Nhân 20 Luồng, 24MB Cache, LGA 1700)', 'CPU-INTEL-14600K', 'cpu-intel-core-i5-14600k', 8490000.00, 7890000.00, 25, 'cpu_i5_14600k.jpg', 'Bộ vi xử lý Intel Core i5 thế hệ 14 Raptor Lake Refresh cực mạnh cho Gaming và Render.', '<p>Intel Core i5-14600K là chiếc CPU chiến lược dành cho game thủ và nhà sáng tạo nội dung. Sở hữu 14 nhân (6 P-Core và 8 E-Core) cùng 20 luồng xử lý, xung nhịp turbo boost lên tới 5.3GHz mang lại hiệu năng đơn nhân và đa nhân vượt trội.</p><p>Sản phẩm tương thích tốt với các dòng bo mạch chủ chipset B760, Z790 và hỗ trợ cả hai chuẩn RAM DDR4 lẫn DDR5.</p>', 1, 1, 350),

(2, 1, 2, 'CPU AMD Ryzen 7 7800X3D (Up to 5.0GHz, 8 Nhân 16 Luồng, 104MB Cache, AM5)', 'CPU-AMD-7800X3D', 'cpu-amd-ryzen-7-7800x3d', 11990000.00, 10490000.00, 18, 'cpu_r7_7800x3d.jpg', 'Vua CPU Gaming với công nghệ 3D V-Cache độc quyền thế hệ mới từ AMD.', '<p>AMD Ryzen 7 7800X3D được mệnh danh là ông vua của các dàn PC Gaming hiện nay nhờ bộ nhớ đệm khổng lồ 104MB L3 Cache công nghệ 3D V-Cache xếp chồng tiên tiến. Hiệu năng trong các tựa game eSports cũng như AAA bỏ xa các đối thủ cùng phân khúc trong khi lượng điện năng tiêu thụ cực kỳ tiết kiệm.</p>', 1, 1, 520),

(3, 2, 3, 'Bo Mạch Chủ ASUS ROG STRIX B760-F GAMING WIFI (Socket LGA1700, DDR5, PCIe 5.0)', 'MAIN-ASUS-B760F', 'bo-mach-chu-asus-rog-strix-b760-f-gaming-wifi', 6390000.00, 5890000.00, 15, 'main_asus_b760f.jpg', 'Mainboard gaming cao cấp trang bị VRM 16+1 pha nguồn, WiFi 6E và âm thanh ROG SupremeFX.', '<p>ASUS ROG STRIX B760-F GAMING WIFI sở hữu thiết kế tông màu đen huyền bí đậm chất game thủ, tản nhiệt VRM dày bản giúp duy trì hiệu năng cao nhất cho các vi xử lý Intel Core i7, i9 non-K hoặc dòng K chạy ngầm định. Trang bị 3 khe M.2 PCIe 4.0 tản nhiệt nhôm nguyên khối.</p>', 1, 1, 240),

(4, 2, 4, 'Bo Mạch Chủ MSI MAG B650 TOMAHAWK WIFI (Socket AM5, DDR5, ATX)', 'MAIN-MSI-B650-TOMAHAWK', 'bo-mach-chu-msi-mag-b650-tomahawk-wifi', 5790000.00, 5290000.00, 20, 'main_msi_b650_tomahawk.jpg', 'Bo mạch chủ socket AM5 chuẩn quân đội, cấp nguồn ổn định và hỗ trợ DDR5 Bus 6400MHz+.', '<p>Dòng TOMAHAWK luôn là tượng đài về sự bền bỉ và hiệu năng trên giá thành. MAG B650 TOMAHAWK WIFI trang bị hệ thống tản nhiệt mở rộng, hỗ trợ RAM DDR5 và băng thông mạng không dây Wi-Fi 6E cùng cổng LAN 2.5Gbps cực nhanh.</p>', 0, 1, 195),

(5, 3, 6, 'RAM Corsair Vengeance RGB 32GB (2x16GB) DDR5 Bus 6000MHz C36 White', 'RAM-COR-32G-D5-RGB', 'ram-corsair-vengeance-rgb-32gb-ddr5-6000mhz-white', 3490000.00, 3190000.00, 40, 'ram_corsair_vengeance_d5.jpg', 'Bộ nhớ RAM DDR5 tốc độ cao với dải LED RGB 10 bóng đa vùng sống động.', '<p>Kit RAM Corsair Vengeance RGB DDR5 32GB tối ưu tuyệt đối cho bo mạch chủ Intel và AMD mới nhất. Hỗ trợ Intel XMP 3.0 và AMD EXPO giúp bạn dễ dàng bật hiệu năng tối đa chỉ bằng một cú nhấp chuột trong BIOS.</p>', 1, 1, 310),

(6, 3, 7, 'RAM Kingston Fury Beast RGB 16GB (1x16GB) DDR4 Bus 3200MHz', 'RAM-KST-16G-D4-RGB', 'ram-kingston-fury-beast-rgb-16gb-ddr4-3200', 1190000.00, 990000.00, 60, 'ram_kingston_fury_d4.jpg', 'Lựa chọn RAM DDR4 quốc dân giá rẻ, tản nhiệt nhôm đen mạnh mẽ cùng LED RGB bắt mắt.', '<p>Kingston FURY Beast DDR4 RGB đem lại sự tăng cường mạnh mẽ về hiệu năng lẫn phong cách cho các cấu hình PC tầm trung. Đồng bộ hóa LED RGB mượt mà bằng công nghệ FURY Infrared Sync độc quyền của Kingston.</p>', 0, 1, 410),

(7, 4, 3, 'Card Màn Hình ASUS TUF Gaming GeForce RTX 4070 SUPER 12GB GDDR6X', 'VGA-ASUS-RTX4070S-TUF', 'vga-asus-tuf-geforce-rtx-4070-super-12gb', 19990000.00, 18490000.00, 12, 'vga_asus_4070s_tuf.jpg', 'Sức mạnh đồ họa đỉnh cao với kiến trúc Ada Lovelace, hỗ trợ DLSS 3.5 và ray tracing sống động.', '<p>ASUS TUF Gaming GeForce RTX 4070 SUPER là chiếc card đồ họa hoàn hảo để chinh chiến mọi tựa game ở độ phân giải 2K và 4K. Hệ thống tản nhiệt 3 quạt Axial-tech ổ bi kép cùng khung kim loại gia cố chắc chắn đem lại độ bền chuẩn quân đội.</p>', 1, 1, 880),

(8, 4, 4, 'Card Màn Hình MSI GeForce RTX 4060 VENTUS 2X Black 8G OC', 'VGA-MSI-RTX4060-V2X', 'vga-msi-geforce-rtx-4060-ventus-2x-8g-oc', 8890000.00, 7990000.00, 30, 'vga_msi_4060_ventus.jpg', 'Card đồ họa quốc dân thế hệ mới tối ưu cho màn hình Full HD và 2K, tiêu thụ điện cực thấp.', '<p>MSI GeForce RTX 4060 VENTUS 2X mang lại trải nghiệm chiến game mượt mà với công nghệ DLSS 3 Frame Generation đột phá. Thiết kế hai quạt TORX FAN 4.0 nhỏ gọn phù hợp với đa số vỏ case mATX và ITX.</p>', 1, 1, 640),

(9, 5, 8, 'Ổ Cứng SSD Samsung 990 PRO 1TB PCIe NVMe 4.0 x4 (Đọc 7450MB/s - Ghi 6900MB/s)', 'SSD-SAM-990PRO-1TB', 'ssd-samsung-990-pro-1tb-pcie-nvme-40', 3290000.00, 2790000.00, 45, 'ssd_samsung_990pro.jpg', 'Ổ cứng SSD NVMe Gen 4 nhanh nhất thế giới của Samsung với bộ điều khiển Pascal tiên tiến.', '<p>SSD Samsung 990 PRO đạt tốc độ đọc tuần tự lên đến 7450 MB/s và ghi 6900 MB/s, tiệm cận giới hạn lý thuyết của băng thông PCIe 4.0. Thích hợp cho game thủ chuyên nghiệp, đồ họa 3D, dựng phim 4K/8K.</p>', 1, 1, 470),

(10, 5, 7, 'Ổ Cứng SSD Kingston NV2 1TB M.2 PCIe Gen 4.0 NVMe', 'SSD-KST-NV2-1TB', 'ssd-kingston-nv2-1tb-m2-pcie-gen-4', 1890000.00, 1590000.00, 50, 'ssd_kingston_nv2_1tb.jpg', 'Giải pháp lưu trữ NVMe Gen 4 dung lượng lớn giá tốt nhất thị trường cho mọi dàn PC.', '<p>Kingston NV2 PCIe 4.0 NVMe là giải pháp nâng cấp lưu trữ tối ưu với tốc độ đọc ghi lên tới 3500/2100 MB/s, kích thước nhỏ gọn một mặt m.2 2280 tiết kiệm diện tích.</p>', 0, 1, 380),

(11, 6, 9, 'Nguồn Máy Tính Seasonic FOCUS GX-750 750W 80 Plus Gold Full Modular', 'PSU-SEA-GX750', 'nguon-may-tinh-seasonic-focus-gx-750-750w-80-plus-gold', 3290000.00, 2990000.00, 20, 'psu_seasonic_gx750.jpg', 'Bộ nguồn chuẩn 80 Plus Gold danh tiếng thế giới, tụ điện Nhật Bản 100%, bảo hành 10 năm.', '<p>Seasonic FOCUS GX-750 cung cấp dòng điện một chiều DC sạch, ổn định tuyệt đối với hiệu suất đạt chuẩn 80 Plus Gold. Thiết kế cáp rời toàn bộ (Full Modular) giúp việc đi dây trong case gọn gàng thẩm mỹ.</p>', 1, 1, 260),

(12, 6, 4, 'Nguồn Máy Tính MSI MAG A650BN 650W 80 Plus Bronze', 'PSU-MSI-A650BN', 'nguon-may-tinh-msi-mag-a650bn-650w-80-plus-bronze', 1450000.00, 1250000.00, 35, 'psu_msi_a650bn.jpg', 'Nguồn máy tính phổ thông công suất thực 650W, chuẩn 80 Plus Bronze đáng tin cậy.', '<p>MSI MAG A650BN mang đến lựa chọn an toàn cho hệ thống Gaming tầm trung với mạch DC to DC hiện đại, quạt 120mm êm ái và các cơ chế bảo vệ mạch toàn diện OVP, OCP, OPP, OTP, SCP.</p>', 0, 1, 180),

(13, 7, 10, 'Vỏ Case NZXT H5 Flow Black (Mid Tower, 2 Quạt F120Q Đi Kèm)', 'CASE-NZXT-H5FLOW-BK', 'vo-case-nzxt-h5-flow-black', 2390000.00, 2150000.00, 15, 'case_nzxt_h5_flow.jpg', 'Vỏ máy tính thiết kế mặt lưới thoáng khí với quạt hướng đáy thổi thẳng vào Card VGA.', '<p>NZXT H5 Flow nổi tiếng với ngôn ngữ thiết kế tối giản, tinh tế cùng luồng gió khí động học đột phá. Kính cường lực bên hông tháo lắp không cần ốc vít, hỗ trợ radiator tản nhiệt nước lên tới 280mm ở mặt trước.</p>', 1, 1, 290),

(14, 8, 11, 'Tản Nhiệt Khí Thermalright Peerless Assassin 120 SE ARGB', 'COOL-TR-PA120SE-ARGB', 'tan-nhiet-khi-thermalright-peerless-assassin-120-se-argb', 950000.00, 790000.00, 30, 'cool_thermalright_pa120se.jpg', 'Vua tản nhiệt khí phân khúc dưới 1 triệu đồng với 6 ống đồng dẫn nhiệt mạ niken kép.', '<p>Thermalright Peerless Assassin 120 SE ARGB sở hữu 2 tháp tản nhiệt dày đặc cùng 2 quạt 120mm PWM LED ARGB, khả năng cân tốt các vi xử lý Core i5/i7 và Ryzen 7 khi chạy tải nặng mà vẫn êm ru.</p>', 1, 1, 430),

(15, 8, 10, 'Tản Nhiệt Nước AIO NZXT Kraken 240 RGB Black (LCD Display)', 'COOL-NZXT-KR240-RGB', 'tan-nhiet-nuoc-aio-nzxt-kraken-240-rgb-black', 4290000.00, 3890000.00, 10, 'cool_nzxt_kraken_240.jpg', 'Tản nhiệt nước AIO cao cấp trang bị màn hình LCD 1.54 inch hiển thị nhiệt độ và ảnh GIF động.', '<p>NZXT Kraken 240 RGB mang lại hiệu năng tản nhiệt đẳng cấp cùng khả năng tùy biến màn hình LCD cực chất qua phần mềm NZXT CAM. Bơm Asetek thế hệ 7 vận hành tĩnh lặng và bền bỉ theo thời gian.</p>', 0, 1, 310),

(16, 9, 12, 'Màn Hình Gaming LG UltraGear 27GR75Q-B 27 inch 2K IPS 165Hz 1ms', 'MON-LG-27GR75Q', 'man-hinh-gaming-lg-ultragear-27gr75q-b-27-inch-2k-165hz', 7490000.00, 6290000.00, 14, 'mon_lg_27gr75q.jpg', 'Màn hình chuẩn Gaming độ phân giải QHD 2K, tấm nền IPS 165Hz tốc độ phản hồi 1ms GtG.', '<p>LG UltraGear 27GR75Q-B được trang bị tấm nền IPS sắc nét độ phủ màu sRGB 99%, chuẩn HDR10, tương thích công nghệ chống xé hình NVIDIA G-SYNC Compatible và AMD FreeSync Premium.</p>', 1, 1, 510),

(17, 10, 13, 'Bàn Phím Cơ Logitech G PRO X TKL LIGHTSPEED Wireless Pink/Black', 'KB-LOG-GPROX-TKL', 'ban-phim-co-logitech-g-pro-x-tkl-lightspeed-wireless', 4490000.00, 3890000.00, 16, 'kb_logitech_gprox_tkl.jpg', 'Bàn phím cơ thể thao điện tử chuyên nghiệp không dây LIGHTSPEED siêu tốc độ.', '<p>Logitech G PRO X TKL là thành quả phối hợp cùng các tuyển thủ eSports hàng đầu thế giới. Switch GX Brown xúc giác, keycap PBT Doubleshot bền bỉ và thời lượng pin sạc lên tới 50 giờ chơi liên tục.</p>', 1, 1, 340),

(18, 10, 15, 'Bàn Phím Cơ DareU EK87 Multi LED Brown Switch', 'KB-DAREU-EK87', 'ban-phim-co-dareu-ek87-multi-led-brown-switch', 650000.00, 499000.00, 40, 'kb_dareu_ek87.jpg', 'Bàn phím cơ quốc dân 87 phím nhỏ gọn, switch D-Switch độc quyền bấm cực đầm tay.', '<p>DareU EK87 sở hữu thiết kế Tenkeyless gọn gàng, hệ thống đèn nền Rainbow nhiều hiệu ứng, nắp phím ABS Double-shot không bao giờ mờ chữ, sự lựa chọn số 1 cho học sinh, sinh viên và văn phòng.</p>', 0, 1, 620),

(19, 11, 13, 'Chuột Không Dây Logitech G PRO X SUPERLIGHT 2 Lightspeed Black', 'MOUSE-LOG-GPX2', 'chuot-khong-day-logitech-g-pro-x-superlight-2', 3890000.00, 3390000.00, 22, 'mouse_logitech_gpx2.jpg', 'Chuột gaming eSports nhẹ nhất thế giới chỉ 60g, cảm biến HERO 2 32000 DPI siêu chuẩn.', '<p>Logitech G PRO X SUPERLIGHT 2 nâng cấp switch lai quang cơ LIGHTFORCE vừa có tốc độ phản hồi tức thì của quang học vừa giữ được cảm giác bấm nảy cơ học. Tần số phản hồi không dây 2000Hz đỉnh cao.</p>', 1, 1, 720),

(20, 11, 14, 'Chuột Gaming Razer DeathAdder Essential White Edition (6400 DPI)', 'MOUSE-RZ-DA-ESS-WH', 'chuot-gaming-razer-deathadder-essential-white', 690000.00, 450000.00, 50, 'mouse_razer_da_essential.jpg', 'Chuột gaming thiết kế công thái học kinh điển từ Razer, cảm biến 6400 DPI siêu nhạy.', '<p>Razer DeathAdder Essential giữ vững phom dáng công thái học huyền thoại vừa vặn lòng bàn tay game thủ, độ bền nút bấm 10 triệu lần click và đèn LED Razer Green cá tính.</p>', 0, 1, 480),

(21, 12, 14, 'Tai Nghe Gaming Razer BlackShark V2 X Multi-Platform Wired', 'HS-RZ-BSV2X', 'tai-nghe-gaming-razer-blackshark-v2-x', 1490000.00, 1090000.00, 25, 'headset_razer_blackshark.jpg', 'Tai nghe thể thao điện tử âm thanh vòm 7.1, driver TriForce 50mm, đệm tai thoáng khí.', '<p>Razer BlackShark V2 X đem lại âm thanh sống động tách bạch 3 dải âm bass, mid, treble. Micro định hướng HyperClear Cardioid thu âm giọng nói rõ nét và cách âm thụ động tuyệt hảo.</p>', 1, 1, 280),

(22, 13, 13, 'Loa Vi Tính Logitech Z407 2.1 Bluetooth Có Subwoofer 80W Peak', 'SPK-LOG-Z407', 'loa-vi-tinh-logitech-z407-21-bluetooth', 2190000.00, 1850000.00, 18, 'spk_logitech_z407.jpg', 'Hệ thống loa 2.1 âm bass uy lực kèm núm xoay điều khiển không dây tầm xa 20m.', '<p>Logitech Z407 mang đến công suất cực đại 80 watt cho âm trầm sâu lắng tràn ngập căn phòng. Hỗ trợ đa dạng kết nối: Bluetooth, cổng Micro USB và giắc cắm 3.5mm tiện dụng.</p>', 0, 1, 190),

(23, 14, 3, 'Giá Treo Tai Nghe ASUS ROG Throne Qi Có Sạc Không Dây & LED Aura Sync', 'ACC-ASUS-THRONE', 'gia-treo-tai-nghe-asus-rog-throne-qi', 3190000.00, 2690000.00, 12, 'acc_asus_throne.jpg', 'Giá đỡ tai nghe tích hợp sạc không dây chuẩn Qi, chip giải mã âm thanh DAC ESS 9118 và USB Hub.', '<p>ASUS ROG Throne Qi nâng tầm góc setup Gaming của bạn với đế sạc nhanh không dây Qi, tích hợp DAC âm thanh vòm 7.1 và hiệu ứng LED Aura Sync 18 vùng độc lập rực rỡ.</p>', 0, 1, 110),

(24, 14, 11, 'Keo Tản Nhiệt Cao Cấp Thermalright TF8 2g (13.8 W/m.k)', 'ACC-TR-TF8', 'keo-tan-nhiet-thermalright-tf8-2g', 180000.00, 140000.00, 0, 'acc_thermalright_tf8.jpg', 'Keo dẫn nhiệt cao cấp với độ dẫn nhiệt cực cao 13.8 W/m.k, không dẫn điện an toàn tuyệt đối.', '<p>Thermalright TF8 là dòng keo tản nhiệt hiệu năng cao dành cho overclocker và game thủ muốn hạ nhiệt tối đa cho CPU và GPU. Độ bền cao không bị khô rạn theo thời gian. (Tạm hết hàng để kiểm tra tính năng out of stock).</p>', 0, 1, 140);

-- 5. Product Specs (Realistic Technical Specifications)
INSERT INTO `product_specs` (`product_id`, `spec_name`, `spec_value`, `sort_order`) VALUES
(1, 'Thương hiệu', 'Intel', 1),
(1, 'Socket', 'LGA 1700', 2),
(1, 'Số nhân / Số luồng', '14 Nhân (6 P-Core + 8 E-Core) / 20 Luồng', 3),
(1, 'Xung nhịp', '3.5 GHz (Tối đa 5.3 GHz Turbo Boost)', 4),
(1, 'Bộ nhớ đệm L3 Cache', '24 MB Intel Smart Cache', 5),
(1, 'Hỗ trợ RAM', 'DDR4 3200MHz / DDR5 5600MHz', 6),
(1, 'Điện năng tiêu thụ (TDP)', '125W (Turbo 181W)', 7),

(2, 'Thương hiệu', 'AMD', 1),
(2, 'Socket', 'AM5', 2),
(2, 'Số nhân / Số luồng', '8 Nhân / 16 Luồng', 3),
(2, 'Xung nhịp', '4.2 GHz (Tối đa 5.0 GHz Precision Boost)', 4),
(2, 'Bộ nhớ đệm L3 Cache', '96MB 3D V-Cache (Tổng 104MB Cache)', 5),
(2, 'Hỗ trợ RAM', 'DDR5 Dual Channel', 6),
(2, 'Điện năng tiêu thụ (TDP)', '120W', 7),

(3, 'Thương hiệu', 'ASUS', 1),
(3, 'Chipset', 'Intel B760', 2),
(3, 'Socket', 'LGA 1700', 3),
(3, 'Kích thước', 'ATX Form Factor', 4),
(3, 'Khe cắm RAM', '4x DDR5 (Tối đa 192GB, Bus 7800+ OC)', 5),
(3, 'Khe cắm M.2 NVMe', '3x M.2 PCIe 4.0 x4', 6),
(3, 'Kết nối không dây', 'Wi-Fi 6E + Bluetooth 5.3', 7),

(7, 'Thương hiệu', 'ASUS / NVIDIA', 1),
(7, 'Nhân đồ họa', 'NVIDIA GeForce RTX 4070 SUPER', 2),
(7, 'Bộ nhớ VRAM', '12GB GDDR6X 192-bit', 3),
(7, 'Xung nhịp OC Mode', '2595 MHz', 4),
(7, 'Cổng kết nối', '1x HDMI 2.1a, 3x DisplayPort 1.4a', 5),
(7, 'Nguồn đề xuất', '750W (Cổng nguồn 16-pin 12VHPWR)', 6),
(7, 'Kích thước', '301 x 139 x 63 mm (3.15 Slot)', 7),

(9, 'Thương hiệu', 'Samsung', 1),
(9, 'Dung lượng', '1 TB (1000 GB)', 2),
(9, 'Chuẩn giao tiếp', 'PCIe Gen 4.0 x4, NVMe 2.0', 3),
(9, 'Kích thước', 'M.2 2280', 4),
(9, 'Tốc độ đọc tuần tự', 'Lên đến 7450 MB/s', 5),
(9, 'Tốc độ ghi tuần tự', 'Lên đến 6900 MB/s', 6),
(9, 'Độ bền TBW', '600 TBW', 7),

(16, 'Thương hiệu', 'LG', 1),
(16, 'Kích thước hiển thị', '27 inch', 2),
(16, 'Độ phân giải', 'QHD (2560 x 1440)', 3),
(16, 'Tấm nền', 'IPS cao cấp', 4),
(16, 'Tần số quét', '165Hz', 5),
(16, 'Thời gian phản hồi', '1ms (GtG)', 6),
(16, 'Đồng bộ hình ảnh', 'NVIDIA G-SYNC Compatible, AMD FreeSync', 7);

-- 6. Sample Orders (To make Admin statistics immediate and accurate)
INSERT INTO `orders` (`id`, `order_code`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `notes`, `payment_method`, `payment_status`, `status`, `subtotal`, `shipping_fee`, `total_amount`, `created_at`) VALUES
(1, 'ORD-20261001-8841', 2, 'Nguyễn Văn An', 'khachhang@gmail.com', '0912345678', '45 Lê Duẩn, P. Thạch Thang, Q. Hải Châu, TP. Đà Nẵng', 'Giao hàng giờ hành chính giúp tôi', 'cod', 'paid', 'completed', 8880000.00, 0.00, 8880000.00, '2026-10-25 14:20:00'),
(2, 'ORD-20261002-3195', NULL, 'Trần Minh Tuấn', 'tuan.tran@gmail.com', '0905999888', '124 Hoàng Diệu, Q. Hải Châu, TP. Đà Nẵng', 'Gọi trước khi giao', 'bank_transfer', 'unpaid', 'pending', 3190000.00, 0.00, 3190000.00, '2026-10-27 10:15:00');

-- 7. Sample Order Items
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `price`, `quantity`, `total`) VALUES
(1, 1, 1, 'CPU Intel Core i5-14600K', 'CPU-INTEL-14600K', 7890000.00, 1, 7890000.00),
(2, 1, 6, 'RAM Kingston Fury Beast RGB 16GB DDR4 Bus 3200', 'RAM-KST-16G-D4-RGB', 990000.00, 1, 990000.00),
(3, 2, 5, 'RAM Corsair Vengeance RGB 32GB (2x16GB) DDR5 Bus 6000MHz White', 'RAM-COR-32G-D5-RGB', 3190000.00, 1, 3190000.00);

-- 8. Order Status History
INSERT INTO `order_status_history` (`order_id`, `status`, `note`, `created_by`, `created_at`) VALUES
(1, 'pending', 'Khách hàng đặt hàng trực tuyến', 'Nguyễn Văn An', '2026-10-25 14:20:00'),
(1, 'confirmed', 'Đã xác nhận đơn hàng qua điện thoại', 'Quản Trị Viên', '2026-10-25 15:00:00'),
(1, 'shipping', 'Giao hàng cho shipper nội thành Đà Nẵng', 'Quản Trị Viên', '2026-10-25 16:30:00'),
(1, 'completed', 'Khách đã nhận hàng và thanh toán COD thành công', 'Quản Trị Viên', '2026-10-25 17:45:00'),
(2, 'pending', 'Khách hàng đặt đơn hàng thanh toán chuyển khoản', 'Trần Minh Tuấn', '2026-10-27 10:15:00');

-- 9. News Categories
INSERT INTO `news_categories` (`id`, `name`, `slug`) VALUES
(1, 'Tin Thị Trường Phần Cứng', 'tin-thi-truong-phan-cung'),
(2, 'Đánh Giá & Trải Nghiệm', 'danh-gia-trai-nghiem'),
(3, 'Hướng Dẫn Build PC', 'huong-dan-build-pc'),
(4, 'Xu Hướng Công Nghệ Mới', 'xu-huong-cong-nghe-moi');

-- 10. News Articles (Rich tech content)
INSERT INTO `news` (`id`, `category_id`, `user_id`, `title`, `slug`, `thumbnail`, `summary`, `content`, `author_name`, `is_published`, `view_count`, `published_at`) VALUES
(1, 1, 1, 'Xu Hướng Giá RAM DDR5 Và Card Màn Hình RTX 40 Series Cuối Năm 2026 Tại Đà Nẵng', 'xu-huong-gia-ram-ddr5-va-vga-rtx-40-series-tai-da-nang', 'news_market_trend.jpg', 'Tổng hợp phân tích biến động giá linh kiện máy tính, nguồn cung chip nhớ và thời điểm vàng để nâng cấp dàn máy chiến game.', '<p>Trong những tháng gần đây, thị trường linh kiện PC tại Đà Nẵng và miền Trung ghi nhận sự ổn định đáng kể về giá bán các dòng card đồ họa NVIDIA RTX 40 Super và bộ nhớ RAM chuẩn DDR5 thế hệ mới.</p><h3>1. Giá RAM DDR5 đã tiếp cận ngưỡng phổ thông</h3><p>Nhờ năng lực sản xuất của các hãng sản xuất chip nhớ hàng đầu như SK Hynix, Micron và Samsung tăng trưởng mạnh, giá của các bộ RAM DDR5 bus 5600MHz - 6000MHz hiện nay chỉ còn chênh lệch khoảng 15-20% so với DDR4 tương đương. Đây là tín hiệu vô cùng tích cực cho người dùng chuẩn bị ráp dàn máy mới trên socket LGA1700 hoặc AM5.</p><h3>2. Dòng card đồ họa NVIDIA RTX 4070 Super giữ ngôi vương hiệu năng / giá</h3><p>Với mức giá dao động quanh mốc 18 đến 19 triệu đồng, RTX 4070 Super 12GB mang đến sức mạnh tiệm cận với RTX 4070 Ti bản cũ nhưng tiết kiệm điện năng hơn đáng kể và hỗ trợ đầy đủ công nghệ nội suy khung hình DLSS 3.5, trở thành sự lựa chọn hàng đầu cho các cấu hình PC gaming 2K tại Đà Nẵng.</p>', 'Kỹ Sư Phần Cứng DaNang PC', 1, 480, '2026-10-20 08:30:00'),

(2, 3, 1, 'Hướng Dẫn Lựa Chọn Nguồn Máy Tính (PSU) Chuẩn Nhất Cho Dàn PC Gaming', 'huong-dan-lua-chon-nguon-may-tinh-chuan-nhat', 'news_psu_guide.jpg', 'Bộ nguồn được ví như trái tim của toàn bộ cỗ máy tính. Đừng để bộ nguồn kém chất lượng làm tổn hại linh kiện đắt tiền của bạn.', '<p>Rất nhiều người dùng khi tự build PC thường dồn ngân sách vào CPU và Card màn hình mà bỏ qua một linh kiện mang tính quyết định độ bền của cả hệ thống: Bộ nguồn máy tính (PSU).</p><h3>Cách tính toán công suất nguồn phù hợp</h3><p>Công thức cơ bản được các chuyên gia kỹ thuật khuyến nghị là: <strong>Công suất nguồn = (TDP CPU + TDP GPU + 150W cho các linh kiện khác) x 1.3</strong>. Hệ số 1.3 sẽ tạo ra khoảng dự phòng giúp bộ nguồn hoạt động ở dải tải lý tưởng 50% - 75%, nơi mà hiệu suất chuyển đổi đạt mức cao nhất và quạt tản nhiệt chạy êm nhất.</p><h3>Ý nghĩa của chứng chỉ 80 Plus</h3><p>Chứng chỉ 80 Plus (White, Bronze, Silver, Gold, Platinum) biểu thị mức độ thất thoát điện năng chuyển hóa thành nhiệt lượng. Với các dàn máy từ tầm trung trở lên trang bị RTX 4060 hay RTX 4070, bạn nên ưu tiên nguồn chuẩn 80 Plus Bronze hoặc 80 Plus Gold từ các thương hiệu uy tín như Seasonic, MSI, Corsair.</p>', 'Ban Biên Tập DaNang PC', 1, 320, '2026-10-22 14:15:00'),

(3, 2, 1, 'So Sánh Chi Tiết Intel Core i5-14600K vs AMD Ryzen 7 7800X3D: Đâu Là Lựa Chọn Tối Ưu?', 'so-sanh-i5-14600k-vs-ryzen-7-7800x3d', 'news_cpu_battle.jpg', 'Trận quyết chiến giữa hai con chip đang được săn đón nhiều nhất trong phân khúc cận cao cấp năm nay.', '<p>Khi xây dựng một cấu hình PC tầm 30 đến 40 triệu đồng, câu hỏi phổ biến nhất tại showroom DaNang PC là: "Nên chọn Intel Core i5-14600K hay AMD Ryzen 7 7800X3D?"</p><h3>Về hiệu năng Gaming thuần túy</h3><p>Nhờ dung lượng bộ nhớ đệm khổng lồ 96MB 3D V-Cache xếp chồng 3 chiều, AMD Ryzen 7 7800X3D chiếm ưu thế áp đảo ở hầu hết các tựa game bắn súng góc nhìn thứ nhất (CS2, Valorant) và game thế giới mở (Cyberpunk 2077, Black Myth: Wukong), mang lại chỉ số FPS 1% low cực kỳ ổn định.</p><h3>Về công việc đa nhiệm, dựng video và đồ họa</h3><p>Ngược lại, với kết cấu 14 nhân và 20 luồng xử lý, Intel Core i5-14600K lại thể hiện sức mạnh vượt trội khi render Premiere Pro, Blender, chạy máy ảo đa nhiệm hoặc nén file nặng.</p>', 'Chuyên Viên Đánh Giá TechPC', 1, 610, '2026-10-24 16:45:00'),

(4, 4, 1, 'PCIe Gen 5 SSD Đã Thực Sự Đáng Mua Chưa Hay Vẫn Nên Chọn Gen 4?', 'pcie-gen-5-ssd-da-thuc-su-dang-mua-chua', 'news_ssd_gen5.jpg', 'Phân tích nhiệt độ, tốc độ thực tế và bài toán chi phí của chuẩn lưu trữ PCIe Gen 5 đối với game thủ.', '<p>Với tốc độ đọc ghi có thể chạm mốc 14.000 MB/s, các dòng SSD PCIe Gen 5 nghe có vẻ rất hấp dẫn trên thông số kỹ thuật. Tuy nhiên, liệu bạn có thực sự cần nó cho nhu cầu chơi game hàng ngày?</p><p>Thực tế cho thấy, thời gian load màn chơi trong game giữa SSD Gen 4 cao cấp (như Samsung 990 Pro 7450 MB/s) và SSD Gen 5 chỉ chênh lệch chưa tới 0.5 giây, trong khi giá thành của Gen 5 cao gấp đôi và đòi hỏi tản nhiệt khối rất lớn để tránh hiện tượng thermal throttling (nghẽn nhiệt).</p>', 'Ban Biên Tập DaNang PC', 1, 290, '2026-10-26 11:00:00');

-- 11. Contact Messages
INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
(1, 'Lê Quốc Bảo', 'baole@gmail.com', '0905333444', 'Tư vấn cấu hình PC Gaming 25 triệu tại Hải Châu Đà Nẵng', 'Chào shop, mình cần tư vấn cấu hình PC kèm màn hình tầm 25 triệu để chơi mượt Black Myth Wukong và làm Photoshop. Nhờ shop phản hồi sớm qua Zalo giúp mình nhé!', 'unread');

-- 12. Site Settings (Store configuration and policies)
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `group_name`) VALUES
('site_name', 'DaNang PC - Siêu Thị Linh Kiện & Phụ Kiện Máy Tính', 'general'),
('site_tagline', 'Hệ thống cung cấp linh kiện PC, Build máy chuyên nghiệp số 1 Đà Nẵng', 'general'),
('store_phone', '0905.123.456 - 0236.3888.999', 'contact'),
('store_email', 'contact@danangpc.vn', 'contact'),
('store_address', '123 Nguyễn Văn Linh, P. Nam Dương, Q. Hải Châu, TP. Đà Nẵng', 'contact'),
('working_hours', 'Thứ 2 - Chủ Nhật: 08:00 - 21:00', 'contact'),
('bank_name', 'Ngân hàng TMCP Ngoại thương Việt Nam (Vietcombank)', 'payment'),
('bank_branch', 'Chi nhánh Nam Đà Nẵng', 'payment'),
('bank_account_number', '10123456789', 'payment'),
('bank_account_name', 'CONG TY TNHH CONG NGHE DANANG PC', 'payment'),
('policy_shipping', '<h3>1. Phạm Vi Giao Hàng</h3><p>DaNang PC cung cấp dịch vụ giao hàng tận nơi trên toàn địa bàn TP. Đà Nẵng (Hải Châu, Thanh Khê, Sơn Trà, Ngũ Hành Sơn, Cẩm Lệ, Liên Chiểu, Hòa Vang) và chuyển phát nhanh toàn quốc qua VNPost/Viettel Post.</p><h3>2. Thời Gian Giao Hàng</h3><p>- Nội thành Đà Nẵng: Giao hỏa tốc trong vòng 1 - 2 giờ kể từ khi xác nhận đơn hàng.</p><p>- Ngoại thành và các tỉnh thành khác: Thời gian giao hàng từ 1 - 3 ngày làm việc.</p><h3>3. Cước Phí Vận Chuyển</h3><p>- Miễn phí vận chuyển cho tất cả đơn hàng từ 1.000.000đ trong bán kính 10km tại Đà Nẵng.</p><p>- Đơn hàng dưới 1.000.000đ áp dụng phí vận chuyển đồng giá 30.000đ.</p>', 'policy'),
('policy_warranty', '<h3>1. Cam Kết Bảo Hành Chính Hãng</h3><p>Tất cả sản phẩm linh kiện PC, CPU, VGA, Mainboard, RAM, Nguồn, Màn hình phân phối tại DaNang PC đều là hàng chính hãng 100%, có đầy đủ hóa đơn VAT và tem bảo hành của nhà phân phối chính thức tại Việt Nam (Viễn Sơn, Synnex FPT, Thủy Linh, Dầu Khí, SPC...).</p><h3>2. Thời Gian Bảo Hành Tiêu Chuẩn</h3><p>- CPU Intel / AMD: Bảo hành 36 tháng chính hãng.</p><p>- Mainboard ASUS, MSI, Gigabyte: Bảo hành 36 tháng.</p><p>- RAM Corsair, Kingston: Bảo hành 36 tháng.</p><p>- Nguồn Seasonic, MSI: Bảo hành từ 36 đến 120 tháng tùy dòng.</p><p>- Ổ cứng SSD Samsung, Kingston: Bảo hành 36 - 60 tháng.</p><h3>3. Chính Sách 1 Đổi 1 Trong 30 Ngày Đầu</h3><p>Sản phẩm bị lỗi phần cứng do nhà sản xuất trong vòng 30 ngày đầu tiên kể từ ngày mua sẽ được đổi ngay sản phẩm mới 100%.</p>', 'policy'),
('policy_return', '<h3>1. Điều Kiện Trả Hàng & Hoàn Tiền</h3><p>Khách hàng có quyền yêu cầu đổi trả hoặc hoàn tiền trong các trường hợp sau:</p><p>- Sản phẩm bị giao sai mã, sai quy cách so với đơn đặt hàng.</p><p>- Sản phẩm bị lỗi kỹ thuật ngay khi mở hộp và được kỹ thuật viên DaNang PC xác nhận.</p><p>- Sản phẩm còn nguyên vẹn tem bảo hành, vỏ hộp, catalogue và đầy đủ phụ kiện đi kèm.</p><h3>2. Phương Thức Hoàn Tiền</h3><p>Hoàn tiền qua chuyển khoản ngân hàng trong vòng 24 giờ làm việc kể từ thời điểm tiếp nhận và kiểm tra hoàn tất sản phẩm tại trung tâm bảo hành DaNang PC.</p>', 'policy'),
('policy_privacy', '<h3>1. Thu Thập Thông Tin Cá Nhân</h3><p>DaNang PC cam kết bảo mật tuyệt đối thông tin cá nhân của khách hàng bao gồm: Họ tên, số điện thoại, địa chỉ nhận hàng và địa chỉ email. Thông tin chỉ được sử dụng cho mục đích xử lý đơn hàng, thông báo tình trạng vận chuyển và chăm sóc khách hàng.</p><h3>2. Cam Kết An Toàn Dữ Liệu</h3><p>Chúng tôi tuyệt đối không mua bán, chia sẻ hoặc tiết lộ thông tin của quý khách cho bất kỳ bên thứ ba nào khi chưa có sự đồng ý của quý khách, ngoại trừ các đơn vị vận chuyển đối tác phục vụ việc giao nhận hàng hóa.</p>', 'policy');
