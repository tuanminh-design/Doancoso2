<?php
use App\Helpers\Url;

$siteName     = $siteSettings['site_name'] ?? 'DaNang PC';
$storePhone   = $siteSettings['store_phone'] ?? '0905.123.456';
$storeEmail   = $siteSettings['store_email'] ?? 'contact@danangpc.vn';
$storeAddress = $siteSettings['store_address'] ?? '123 Nguyễn Văn Linh, P. Nam Dương, Q. Hải Châu, TP. Đà Nẵng';
$workingHours = $siteSettings['working_hours'] ?? '08:00 - 21:00 hàng ngày';
?>
<footer class="site-footer">
  <div class="footer-main">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1: Store Info -->
        <div class="footer-col">
          <div style="margin-bottom: 16px;">
            <img src="<?= Url::asset('images/logo.svg') ?>" alt="<?= htmlspecialchars($siteName) ?>" style="filter: brightness(0) invert(1); height: 44px;" />
          </div>
          <p>Hệ thống cung cấp linh kiện máy tính, PC Gaming, phụ kiện máy tính chính hãng uy tín hàng đầu tại TP. Đà Nẵng và khu vực Miền Trung.</p>
          <p><i class="fa-solid fa-location-dot" style="color: #38bdf8; margin-right: 6px;"></i> <?= htmlspecialchars($storeAddress) ?></p>
          <p><i class="fa-solid fa-phone" style="color: #38bdf8; margin-right: 6px;"></i> Hotline: <?= htmlspecialchars($storePhone) ?></p>
          <p><i class="fa-solid fa-envelope" style="color: #38bdf8; margin-right: 6px;"></i> Email: <?= htmlspecialchars($storeEmail) ?></p>
          <p><i class="fa-solid fa-clock" style="color: #38bdf8; margin-right: 6px;"></i> Mở cửa: <?= htmlspecialchars($workingHours) ?></p>
        </div>

        <!-- Col 2: Categories -->
        <div class="footer-col">
          <h4>Linh Kiện Máy Tính</h4>
          <ul>
            <li><a href="<?= Url::to('products', ['category' => 'cpu-bo-vi-xu-ly']) ?>">Bộ vi xử lý (CPU)</a></li>
            <li><a href="<?= Url::to('products', ['category' => 'vga-card-man-hinh']) ?>">Card màn hình (VGA)</a></li>
            <li><a href="<?= Url::to('products', ['category' => 'mainboard-bo-mach-chu']) ?>">Bo mạch chủ (Mainboard)</a></li>
            <li><a href="<?= Url::to('products', ['category' => 'ram-bo-nho-trong']) ?>">Bộ nhớ trong (RAM)</a></li>
            <li><a href="<?= Url::to('products', ['category' => 'o-cung-ssd-hdd']) ?>">Ổ cứng SSD &amp; HDD</a></li>
            <li><a href="<?= Url::to('products', ['category' => 'nguon-may-tinh-psu']) ?>">Nguồn máy tính (PSU)</a></li>
            <li><a href="<?= Url::to('products', ['category' => 'vo-case-may-tinh']) ?>">Vỏ case máy tính</a></li>
          </ul>
        </div>

        <!-- Col 3: Customer Policies -->
        <div class="footer-col">
          <h4>Chính Sách &amp; Hỗ Trợ</h4>
          <ul>
            <li><a href="<?= Url::to('policy/warranty') ?>"><i class="fa-solid fa-angle-right"></i> Chính sách bảo hành chính hãng</a></li>
            <li><a href="<?= Url::to('policy/shipping') ?>"><i class="fa-solid fa-angle-right"></i> Chính sách vận chuyển &amp; giao hàng</a></li>
            <li><a href="<?= Url::to('policy/return') ?>"><i class="fa-solid fa-angle-right"></i> Chính sách đổi trả &amp; hoàn tiền</a></li>
            <li><a href="<?= Url::to('policy/privacy') ?>"><i class="fa-solid fa-angle-right"></i> Chính sách bảo mật thông tin</a></li>
            <li><a href="<?= Url::to('contact') ?>"><i class="fa-solid fa-angle-right"></i> Hướng dẫn thanh toán &amp; liên hệ</a></li>
          </ul>
        </div>

        <!-- Col 4: Quick Access -->
        <div class="footer-col">
          <h4>Truy Cập Nhanh</h4>
          <ul>
            <li><a href="<?= Url::to('/') ?>">Trang chủ</a></li>
            <li><a href="<?= Url::to('products') ?>">Tất cả sản phẩm</a></li>
            <li><a href="<?= Url::to('news') ?>">Tin tức công nghệ mới</a></li>
            <li><a href="<?= Url::to('cart') ?>">Giỏ hàng</a></li>
            <li><a href="<?= Url::to('account/orders') ?>">Kiểm tra tình trạng đơn hàng</a></li>
          </ul>
          <div style="margin-top: 20px;">
            <span style="display: block; font-size: 0.8rem; font-weight: bold; margin-bottom: 8px; color: #ffffff;">PHƯƠNG THỨC THANH TOÁN:</span>
            <div style="display: flex; gap: 8px; font-size: 1.5rem; color: #94a3b8;">
              <i class="fa-solid fa-money-bill-wave" title="Thanh toán COD khi nhận hàng"></i>
              <i class="fa-solid fa-building-columns" title="Chuyển khoản ngân hàng"></i>
              <i class="fa-solid fa-qrcode" title="Quét mã QR VietQR"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Copyright -->
  <div class="footer-bottom">
    <div class="container">
      <div>
        &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($siteName) ?></strong> - Siêu Thị Linh Kiện &amp; Phụ Kiện Máy Tính Đà Nẵng. Bản quyền được bảo hộ.
      </div>
      <div>
        Phát triển chuyên nghiệp với PHP &amp; MySQL
      </div>
    </div>
  </div>
</footer>
