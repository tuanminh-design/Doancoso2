<?php
use App\Helpers\Format;
use App\Helpers\Url;
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <a href="<?= Url::to('account') ?>">Tài khoản</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Lịch Sử Đơn Hàng</span>
  </div>

  <div style="display: grid; grid-template-columns: 260px 1fr; gap: 30px;">
    <!-- Account Nav -->
    <div>
      <div class="card-box" style="padding: 10px 0;">
        <ul style="list-style: none;">
          <li>
            <a href="<?= Url::to('account') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #475569;">
              <i class="fa-solid fa-user-pen"></i> Thông tin cá nhân
            </a>
          </li>
          <li>
            <a href="<?= Url::to('account/orders') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-weight: 600; color: #2563eb; background: #eff6ff;">
              <i class="fa-solid fa-clipboard-list"></i> Lịch sử đơn hàng
            </a>
          </li>
          <li>
            <a href="<?= Url::to('logout') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #dc2626;">
              <i class="fa-solid fa-power-off"></i> Đăng xuất
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Orders List -->
    <div>
      <div class="card-box">
        <h3 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
          <span><i class="fa-solid fa-clock-rotate-left" style="color: #2563eb;"></i> Danh Sách Đơn Hàng Đã Mua</span>
          <span style="font-size: 0.85rem; color: #64748b; font-weight: normal;">Tổng: <?= $totalOrders ?> đơn</span>
        </h3>

        <?php if (!empty($orders)): ?>
          <div class="cart-table-wrap" style="border: none; margin-bottom: 0;">
            <table class="cart-table">
              <thead>
                <tr>
                  <th>Mã Đơn Hàng</th>
                  <th>Ngày Đặt</th>
                  <th style="text-align: right;">Tổng Tiền</th>
                  <th>Phương Thức</th>
                  <th>Trạng Thái</th>
                  <th style="text-align: center;">Thao Tác</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                  <tr>
                    <td>
                      <strong style="color: #2563eb; font-family: monospace;">
                        <?= htmlspecialchars($order['order_code']) ?>
                      </strong>
                    </td>
                    <td style="font-size: 0.85rem; color: #64748b;">
                      <?= Format::datetime($order['created_at']) ?>
                    </td>
                    <td style="text-align: right; font-weight: 700; color: #dc2626;">
                      <?= Format::currency($order['total_amount']) ?>
                    </td>
                    <td style="font-size: 0.85rem;">
                      <?= $order['payment_method'] === 'cod' ? 'COD' : 'Chuyển Khoản' ?>
                    </td>
                    <td>
                      <?= Format::orderStatusBadge($order['status']) ?>
                    </td>
                    <td style="text-align: center;">
                      <a href="<?= Url::to('account/orders/' . $order['order_code']) ?>" class="btn btn-outline btn-sm">
                        <i class="fa-regular fa-eye"></i> Xem
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <?php if ($totalPages > 1): ?>
            <div class="pagination">
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= Url::to('account/orders', ['page' => $i]) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
                  <?= $i ?>
                </a>
              <?php endfor; ?>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 14px;"></i>
            <p>Bạn chưa có đơn đặt hàng nào tại DaNang PC.</p>
            <a href="<?= Url::to('products') ?>" class="btn btn-primary btn-sm" style="margin-top: 10px;">
              Mua sắm ngay
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
