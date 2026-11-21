<?php
/**
 * Application Configuration
 * DaNang PC E-Commerce Website
 */

return [
    'name'        => 'DaNang PC',
    'app_name'    => 'DaNang PC - Siêu Thị Linh Kiện & Phụ Kiện Máy Tính',
    'version'     => '1.0.0',
    'timezone'    => 'Asia/Ho_Chi_Minh',
    'currency'    => '₫',
    'upload_path' => dirname(__DIR__) . '/public/uploads',
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'max_upload_size'     => 5 * 1024 * 1024, // 5MB
];
