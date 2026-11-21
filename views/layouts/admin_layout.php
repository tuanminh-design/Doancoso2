<?php
use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\Order;

$orderModel = new Order();
$pendingCount = $orderModel->getPendingCount();

$uri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Quản Trị Hệ Thống - DaNang PC') ?></title>
  
  <link rel="icon" type="image/svg+xml" href="<?= Url::asset('images/logo.svg') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="<?= Url::asset('css/main.css') ?>">
  <link rel="stylesheet" href="<?= Url::asset('css/admin.css') ?>">
</head>
<body class="admin-body">

  <!-- Admin Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-sidebar-brand">
      <i class="fa-solid fa-server"></i>
      <span>DANANG PC ADMIN</span>
    </div>

    <ul class="admin-nav">
      <li class="admin-nav-item <?= $uri === '/admin' || str_ends_with($uri, '/admin') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin') ?>">
          <i class="fa-solid fa-chart-pie"></i>
          <span>Tổng Quan Dashboard</span>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/orders') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/orders') ?>">
          <i class="fa-solid fa-cart-flatbed"></i>
          <span>Quản Lý Đơn Hàng</span>
          <?php if ($pendingCount > 0): ?>
            <span class="admin-nav-badge"><?= $pendingCount ?></span>
          <?php endif; ?>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/products') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/products') ?>">
          <i class="fa-solid fa-microchip"></i>
          <span>Quản Lý Sản Phẩm</span>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/categories') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/categories') ?>">
          <i class="fa-solid fa-layer-group"></i>
          <span>Danh Mục Linh Kiện</span>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/brands') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/brands') ?>">
          <i class="fa-solid fa-copyright"></i>
          <span>Thương Hiệu (Hãng)</span>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/users') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/users') ?>">
          <i class="fa-solid fa-users"></i>
          <span>Quản Lý Khách Hàng</span>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/news') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/news') ?>">
          <i class="fa-solid fa-newspaper"></i>
          <span>Tin Tức Công Nghệ</span>
        </a>
      </li>

      <li class="admin-nav-item <?= str_contains($uri, '/admin/settings') ? 'active' : '' ?>">
        <a href="<?= Url::to('admin/settings') ?>">
          <i class="fa-solid fa-sliders"></i>
          <span>Cấu Hình Website</span>
        </a>
      </li>

      <li style="margin: 20px 0; border-top: 1px solid rgba(255,255,255,0.08);"></li>

      <li class="admin-nav-item">
        <a href="<?= Url::to('/') ?>" target="_blank">
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
          <span>Xem Cửa Hàng (Storefront)</span>
        </a>
      </li>

      <li class="admin-nav-item">
        <a href="<?= Url::to('logout') ?>" style="color: #f87171;">
          <i class="fa-solid fa-power-off"></i>
          <span>Đăng Xuất</span>
        </a>
      </li>
    </ul>
  </aside>

  <!-- Admin Main -->
  <div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
      <div class="admin-topbar-title">
        <h2><?= htmlspecialchars($pageTitle ?? 'Bảng Điều Khiển') ?></h2>
      </div>

      <div class="admin-topbar-user">
        <div class="admin-user-pill">
          <div class="admin-user-avatar">
            <i class="fa-solid fa-user-shield"></i>
          </div>
          <div>
            <div><?= htmlspecialchars(Auth::name()) ?></div>
            <small style="color: #16a34a; font-weight: bold;">Quản trị viên</small>
          </div>
        </div>
      </div>
    </header>

    <!-- Admin Content Area -->
    <main class="admin-content">
      <!-- Alerts -->
      <?php if (!empty($flashSuccess)): ?>
        <div class="alert alert-success">
          <i class="fa-solid fa-circle-check"></i>
          <div><?= $flashSuccess ?></div>
        </div>
      <?php endif; ?>

      <?php if (!empty($flashError)): ?>
        <div class="alert alert-error">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <div><?= $flashError ?></div>
        </div>
      <?php endif; ?>

      <?= $content ?? '' ?>
    </main>
  </div>

  <script src="<?= Url::asset('js/admin.js') ?>"></script>
</body>
</html>
