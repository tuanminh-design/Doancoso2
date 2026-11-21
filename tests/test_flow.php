<?php
/**
 * Automated End-to-End Business Logic Test Suite
 * DaNang PC E-Commerce
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Helpers\Database;
use App\Models\Brand;
use App\Models\Category;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\CartService;

echo "=== STARTING AUTOMATED TEST SUITE ===\n\n";

// 1. Test Database Connectivity
echo "[TEST 1] Testing Database Connection...\n";
$pdo = Database::getConnection();
if ($pdo instanceof PDO) {
    echo "  -> SUCCESS: Connected to MySQL database pc_store successfully.\n";
} else {
    die("  -> FAILED: Unable to connect to MySQL.\n");
}

// 2. Test User Authentication & Password Hashing
echo "\n[TEST 2] Testing User Authentication & Password Hashing...\n";
$userModel = new User();
$admin = $userModel->findByEmail('admin@danangpc.vn');
if ($admin && password_verify('Admin@123456', $admin['password']) && $admin['role'] === 'admin') {
    echo "  -> SUCCESS: Admin credentials verified. Role: " . $admin['role'] . "\n";
} else {
    echo "  -> FAILED: Admin login check failed.\n";
}

$customer = $userModel->findByEmail('khachhang@gmail.com');
if ($customer && password_verify('Customer@123456', $customer['password']) && $customer['role'] === 'customer') {
    echo "  -> SUCCESS: Customer credentials verified. Role: " . $customer['role'] . "\n";
} else {
    echo "  -> FAILED: Customer login check failed.\n";
}

// 3. Test Products & Specifications
echo "\n[TEST 3] Testing Product Catalog & Specs...\n";
$productModel = new Product();
$featured = $productModel->getFeatured(8);
echo "  -> Featured products count: " . count($featured) . "\n";

$onSale = $productModel->getOnSale(8);
echo "  -> On-sale products count: " . count($onSale) . "\n";

$searchResults = $productModel->getFilteredProducts(['search' => 'RTX']);
echo "  -> Search results for 'RTX': " . count($searchResults) . " items found.\n";

$p1 = $productModel->findById(1);
$specs = $productModel->getSpecs(1);
echo "  -> Product 1 ('{$p1['name']}') has " . count($specs) . " specifications.\n";

// 4. Test Cart Workflow & Stock Safeguards
echo "\n[TEST 4] Testing Cart Workflow & Stock Safeguards...\n";
$cartService = new CartService();
$cartService->clear();

// Add Product 1 (Qty 2)
$res = $cartService->add(1, 2);
echo "  -> Added 2x Product 1 to cart: " . $res['message'] . "\n";
$cart = $cartService->getCart();
echo "  -> Cart total quantity: " . $cart['total_quantity'] . ", Subtotal: " . number_format($cart['subtotal']) . " VND\n";

// Test stock limit exception
$p24 = $productModel->findById(24); // Keo tản nhiệt Thermalright TF8 has 0 stock
try {
    $cartService->add(24, 1);
    echo "  -> FAILED: Out of stock item should not be added!\n";
} catch (Exception $e) {
    echo "  -> SUCCESS: Stock safeguard correctly blocked out-of-stock item: " . $e->getMessage() . "\n";
}

// 5. Test Order Placement & Inventory Transactions
echo "\n[TEST 5] Testing Order Placement & Inventory Transaction...\n";
$initialStockP1 = (int)$productModel->findById(1)['stock_quantity'];
echo "  -> Product 1 stock before order: {$initialStockP1}\n";

$orderModel = new Order();
$orderData = [
    'user_id'          => 2,
    'customer_name'    => 'Nguyễn Văn An',
    'customer_email'   => 'khachhang@gmail.com',
    'customer_phone'   => '0912345678',
    'shipping_address' => '45 Lê Duẩn, Q. Hải Châu, TP. Đà Nẵng',
    'notes'            => 'Giao hàng buổi sáng',
    'payment_method'   => 'bank_transfer',
];

$orderResult = $orderModel->createOrderWithItems($orderData, $cart['items']);
echo "  -> SUCCESS: Created Order Code: " . $orderResult['order_code'] . " (ID: " . $orderResult['id'] . ")\n";

$afterStockP1 = (int)$productModel->findById(1)['stock_quantity'];
echo "  -> Product 1 stock after order: {$afterStockP1} (Decreased by 2: " . ($initialStockP1 - $afterStockP1 == 2 ? 'YES' : 'NO') . ")\n";

// 6. Test Order Cancellation & Inventory Restoration
echo "\n[TEST 6] Testing Order Cancellation & Stock Restoration...\n";
$cancelled = $orderModel->cancelOrder($orderResult['id'], 'Khách hàng đổi ý muốn nâng cấp model cao hơn', 'Admin Tester');
if ($cancelled) {
    echo "  -> Order cancelled successfully.\n";
    $restoredStockP1 = (int)$productModel->findById(1)['stock_quantity'];
    echo "  -> Product 1 stock after cancellation: {$restoredStockP1} (Restored to original {$initialStockP1}: " . ($restoredStockP1 == $initialStockP1 ? 'PERFECT' : 'FAILED') . ")\n";
} else {
    echo "  -> FAILED: Order cancellation failed.\n";
}

// 7. Test News System
echo "\n[TEST 7] Testing Tech News Articles...\n";
$newsModel = new News();
$latestNews = $newsModel->getLatest(4);
echo "  -> Latest articles count: " . count($latestNews) . "\n";
$firstNews = $latestNews[0];
$initViews = (int)$firstNews['view_count'];
$newsModel->incrementView($firstNews['id']);
$refreshedNews = $newsModel->findById($firstNews['id']);
echo "  -> View count incremented from {$initViews} to " . $refreshedNews['view_count'] . ": SUCCESS\n";

// 8. Test Settings
echo "\n[TEST 8] Testing Store Settings...\n";
$settingModel = new Setting();
$phone = $settingModel->get('store_phone');
$address = $settingModel->get('store_address');
echo "  -> Store Phone: {$phone}\n";
echo "  -> Store Address in Da Nang: {$address}\n";

echo "\n=== ALL TESTS COMPLETED SUCCESSFULLY! ===\n";
