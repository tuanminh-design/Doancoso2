<?php
use App\Helpers\Auth;
use App\Helpers\Format;
use App\Helpers\Url;

$siteName = $siteSettings['site_name'] ?? 'DaNang PC';
$storePhone = $siteSettings['store_phone'] ?? '0905.123.456';
$storeAddress = $siteSettings['store_address'] ?? '123 Nguyễn Văn Linh, P. Nam Dương, Q. Hải Châu, TP. Đà Nẵng';
?>
<!-- Top Notice Bar -->
<div class="header-top">
  <div class="container">
    <div class="header-top-info">
      <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($storeAddress) ?></span>
      <span><i class="fa-solid fa-phone"></i> Hotline: <strong><?= htmlspecialchars($storePhone) ?></strong></span>
      <span><i class="fa-solid fa-shield-halved"></i> 100% Linh kiện chính hãng</span>
    </div>
    <div class="header-top-links">
      <?php if (Auth::check()): ?>
        <?php if (Auth::isAdmin()): ?>
          <a href="<?= Url::to('admin') ?>" style="color: #2563eb; font-weight: 700; margin-right: 15px;">
            <i class="fa-solid fa-gauge-high"></i> Vào Trang Quản Trị
          </a>
        <?php endif; ?>
        <a href="<?= Url::to('account/orders') ?>"><i class="fa-solid fa-clipboard-list"></i> Đơn hàng của tôi</a>
      <?php else: ?>
        <a href="<?= Url::to('login') ?>"><i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng nhập</a>
        <span style="margin: 0 6px;">|</span>
        <a href="<?= Url::to('register') ?>"><i class="fa-solid fa-user-plus"></i> Đăng ký</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Main Header -->
<div class="header-main">
  <div class="container">
    <!-- Logo -->
    <div class="header-logo">
      <a href="<?= Url::to('/') ?>" title="<?= htmlspecialchars($siteName) ?>">
        <img src="<?= Url::asset('images/logo.svg') ?>" alt="<?= htmlspecialchars($siteName) ?>" />
      </a>
    </div>

    <!-- Search Bar -->
    <div class="header-search">
      <form action="<?= Url::to('products') ?>" method="GET" class="search-form">
        <input 
          type="text" 
          name="q" 
          class="search-input" 
          placeholder="Tìm CPU, VGA, Mainboard, RAM, Bàn phím cơ..." 
          value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
          required
        />
        <button type="submit" class="search-btn" title="Tìm kiếm">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>
      </form>
    </div>

    <!-- Actions: User & Cart -->
    <div class="header-actions">
      <?php if (Auth::check()): ?>
        <a href="<?= Url::to('account') ?>" class="action-item" title="Quản lý tài khoản">
          <div class="action-icon"><i class="fa-regular fa-circle-user"></i></div>
          <div class="action-text">
            <span class="action-title">Xin chào,</span>
            <span class="action-sub"><?= htmlspecialchars(Format::excerpt(Auth::name(), 12)) ?></span>
          </div>
        </a>
      <?php else: ?>
        <a href="<?= Url::to('login') ?>" class="action-item" title="Đăng nhập tài khoản">
          <div class="action-icon"><i class="fa-regular fa-user"></i></div>
          <div class="action-text">
            <span class="action-title">Tài khoản</span>
            <span class="action-sub">Đăng nhập / Đăng ký</span>
          </div>
        </a>
      <?php endif; ?>

      <!-- Shopping Cart -->
      <a href="<?= Url::to('cart') ?>" class="action-item" title="Xem giỏ hàng">
        <div class="action-icon">
          <i class="fa-solid fa-cart-shopping"></i>
          <span class="cart-badge" style="<?= ($cartCount ?? 0) > 0 ? '' : 'display:none;' ?>">
            <?= $cartCount ?? 0 ?>
          </span>
        </div>
        <div class="action-text">
          <span class="action-title">Giỏ hàng</span>
          <span class="action-sub">Mua sắm</span>
        </div>
      </a>
    </div>
  </div>
</div>

<!-- Category & Main Navigation -->
<nav class="header-nav">
  <div class="container">
    <!-- Category Dropdown -->
    <div class="category-dropdown">
      <div class="category-toggle">
        <i class="fa-solid fa-bars"></i>
        <span>DANH MỤC SẢN PHẨM</span>
        <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; margin-left: auto;"></i>
      </div>
      <ul class="category-menu">
        <?php if (!empty($navCategories)): ?>
          <?php foreach ($navCategories as $cat): ?>
            <li class="category-item">
              <a href="<?= Url::to('products', ['category' => $cat['slug']]) ?>">
                <i class="fa-solid <?= htmlspecialchars($cat['icon'] ?: 'fa-microchip') ?>"></i>
                <span><?= htmlspecialchars($cat['name']) ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Main Links -->
    <ul class="nav-links">
      <li class="nav-link <?= empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] === '/' || str_ends_with($_SERVER['REQUEST_URI'], 'index.php') ? 'active' : '' ?>">
        <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
      </li>
      <li class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'products') ? 'active' : '' ?>">
        <a href="<?= Url::to('products') ?>"><i class="fa-solid fa-boxes-stacked"></i> Tất Cả Sản Phẩm</a>
      </li>
      <li class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'news') ? 'active' : '' ?>">
        <a href="<?= Url::to('news') ?>"><i class="fa-solid fa-newspaper"></i> Tin Tức Công Nghệ</a>
      </li>
      <li class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'contact') ? 'active' : '' ?>">
        <a href="<?= Url::to('contact') ?>"><i class="fa-solid fa-envelope"></i> Liên Hệ</a>
      </li>
    </ul>
  </div>
</nav>
