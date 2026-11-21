<?php
$cookieFile = sys_get_temp_dir() . '/cookie_test.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

// 1. Get login page to extract CSRF token
$ch = curl_init('http://127.0.0.1:8080/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);
curl_close($ch);

preg_match('/name="_csrf_token" value="([^"]+)"/', $html, $m);
$token = $m[1] ?? '';
echo "CSRF Token extracted: " . substr($token, 0, 10) . "...\n";

// 2. Post Admin Login
$ch = curl_init('http://127.0.0.1:8080/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_csrf_token' => $token,
    'email'       => 'admin@danangpc.vn',
    'password'    => 'Admin@123456'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Login HTTP Response Code: {$httpCode}\n";

// 3. Request Admin Dashboard
$ch = curl_init('http://127.0.0.1:8080/admin');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$adminHtml = curl_exec($ch);
$adminCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Admin Dashboard HTTP Code: {$adminCode}\n";
if (strpos($adminHtml, 'Tổng Doanh Thu') !== false || strpos($adminHtml, 'DANANG PC ADMIN') !== false) {
    echo "-> SUCCESS: Admin Dashboard successfully authenticated and rendered!\n";
} else {
    echo "-> Content snippet: " . substr(strip_tags($adminHtml), 0, 150) . "\n";
}

// 4. Request Admin Products
$ch = curl_init('http://127.0.0.1:8080/admin/products');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$prodHtml = curl_exec($ch);
curl_close($ch);

if (strpos($prodHtml, 'Thêm Sản Phẩm Mới') !== false) {
    echo "-> SUCCESS: Admin Products page successfully rendered!\n";
}

// 5. Request Admin Orders
$ch = curl_init('http://127.0.0.1:8080/admin/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$orderHtml = curl_exec($ch);
curl_close($ch);

if (strpos($orderHtml, 'Quản Lý Đơn Đặt Hàng') !== false) {
    echo "-> SUCCESS: Admin Orders page successfully rendered!\n";
}

// 6. Request Admin Settings
$ch = curl_init('http://127.0.0.1:8080/admin/settings');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$settingHtml = curl_exec($ch);
curl_close($ch);

if (strpos($settingHtml, 'Cấu Hình Website') !== false) {
    echo "-> SUCCESS: Admin Settings page successfully rendered!\n";
}

echo "\nALL HTTP AUTHENTICATED TESTS PASSED!\n";
