<?php
use App\Helpers\Format;
use App\Helpers\Url;
?>

<!-- Statistics Overview Cards -->
<div class="stats-grid">
  <!-- Revenue -->
  <div class="stat-card">
    <div class="stat-icon success">
      <i class="fa-solid fa-coins"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Tổng Doanh Thu</span>
      <span class="stat-value"><?= Format::currency($totalRevenue) ?></span>
    </div>
  </div>

  <!-- Orders -->
  <div class="stat-card">
    <div class="stat-icon primary">
      <i class="fa-solid fa-cart-shopping"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Tổng Đơn Hàng</span>
      <span class="stat-value"><?= number_format($totalOrders) ?></span>
    </div>
  </div>

  <!-- Pending Orders -->
  <div class="stat-card">
    <div class="stat-icon warning">
      <i class="fa-solid fa-hourglass-half"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Đơn Chờ Xác Nhận</span>
      <span class="stat-value"><?= number_format($pendingOrders) ?></span>
    </div>
  </div>

  <!-- Products -->
  <div class="stat-card">
    <div class="stat-icon primary">
      <i class="fa-solid fa-microchip"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Tổng Sản Phẩm</span>
      <span class="stat-value"><?= number_format($totalProducts) ?></span>
    </div>
  </div>

  <!-- Customers -->
  <div class="stat-card">
    <div class="stat-icon danger">
      <i class="fa-solid fa-users"></i>
    </div>
    <div class="stat-info">
      <span class="stat-label">Tài Khoản Khách</span>
      <span class="stat-value"><?= number_format($totalCustomers) ?></span>
    </div>
  </div>
</div>

<!-- Quick Action Buttons -->
<div style="display: flex; gap: 14px; margin-bottom: 28px; flex-wrap: wrap;">
  <a href="<?= Url::to('admin/products/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Thêm Sản Phẩm Mới
  </a>
  <a href="<?= Url::to('admin/orders') ?>" class="btn btn-outline">
    <i class="fa-solid fa-boxes-packing"></i> Quản Lý Đơn Hàng
  </a>
  <a href="<?= Url::to('admin/news/create') ?>" class="btn btn-outline">
    <i class="fa-solid fa-pen-to-square"></i> Viết Tin Công Nghệ Mới
  </a>
  <a href="<?= Url::to('admin/settings') ?>" class="btn btn-outline">
    <i class="fa-solid fa-gear"></i> Cấu Hình Website &amp; Ngân Hàng
  </a>
</div>

<!-- Recent Orders Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3 class="admin-card-title">
      <i class="fa-solid fa-clock-rotate-left"></i> Đơn Hàng Gần Đây
    </h3>
    <a href="<?= Url::to('admin/orders') ?>" class="btn btn-sm btn-outline">
      Xem tất cả đơn hàng &rarr;
    </a>
  </div>

  <div class="admin-card-body" style="padding: 0;">
    <?php if (!empty($recentOrders)): ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Mã Đơn Hàng</th>
            <th>Khách Hàng</th>
            <th>Thời Gian</th>
            <th style="text-align: right;">Tổng Tiền</th>
            <th>Thanh Toán</th>
            <th>Trạng Thái</th>
            <th style="text-align: center;">Chi Tiết</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentOrders as $ro): ?>
            <tr>
              <td>
                <strong style="color: #2563eb; font-family: monospace;">
                  <?= htmlspecialchars($ro['order_code']) ?>
                </strong>
              </td>
              <td>
                <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($ro['customer_name']) ?></div>
                <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($ro['customer_phone']) ?></div>
              </td>
              <td style="font-size: 0.82rem; color: #64748b;">
                <?= Format::datetime($ro['created_at']) ?>
              </td>
              <td style="text-align: right; font-weight: 700; color: #dc2626;">
                <?= Format::currency($ro['total_amount']) ?>
              </td>
              <td>
                <?= Format::paymentStatusBadge($ro['payment_status']) ?>
              </td>
              <td>
                <?= Format::orderStatusBadge($ro['status']) ?>
              </td>
              <td style="text-align: center;">
                <a href="<?= Url::to('admin/orders/' . $ro['id']) ?>" class="btn btn-sm btn-outline" title="Xem chi tiết đơn hàng">
                  <i class="fa-regular fa-eye"></i> Xem
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #64748b;">
        Chưa có đơn hàng nào được ghi nhận.
      </div>
    <?php endif; ?>
  </div>
</div>
