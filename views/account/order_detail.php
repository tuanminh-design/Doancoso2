<?php
use App\Helpers\Format;
use App\Helpers\Url;
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <a href="<?= Url::to('account/orders') ?>">Đơn hàng</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['order_code']) ?></span>
  </div>

  <div class="card-box" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; padding-bottom: 18px; border-bottom: 1px solid #e2e8f0;">
      <div>
        <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
          Đơn Hàng: #<?= htmlspecialchars($order['order_code']) ?>
        </h1>
        <span style="font-size: 0.88rem; color: #64748b;">
          Ngày đặt: <?= Format::datetime($order['created_at']) ?>
        </span>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 0.9rem; color: #64748b;">Trạng thái:</span>
        <?= Format::orderStatusBadge($order['status']) ?>
        <?= Format::paymentStatusBadge($order['payment_status']) ?>
      </div>
    </div>

    <!-- Status History Timeline -->
    <?php if (!empty($history)): ?>
      <div style="margin: 20px 0; padding: 16px; background-color: #f8fafc; border-radius: 8px;">
        <h4 style="font-size: 0.92rem; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
          <i class="fa-solid fa-timeline" style="color: #2563eb;"></i> Tiến Trình Xử Lý Đơn Hàng
        </h4>
        <div style="display: flex; flex-direction: column; gap: 10px;">
          <?php foreach ($history as $h): ?>
            <div style="display: flex; gap: 12px; font-size: 0.85rem;">
              <span style="color: #64748b; min-width: 130px;"><?= Format::datetime($h['created_at']) ?></span>
              <span style="font-weight: 600; color: #0f172a;"><?= Format::orderStatusText($h['status']) ?>:</span>
              <span style="color: #475569;"><?= htmlspecialchars($h['note'] ?? '') ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Recipient & Payment Info -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
      <div style="background: #fafbfc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
          <i class="fa-solid fa-location-dot" style="color: #2563eb;"></i> Địa Chỉ Giao Hàng
        </h4>
        <p style="font-size: 0.88rem; margin-bottom: 4px;">Người nhận: <strong><?= htmlspecialchars($order['customer_name']) ?></strong></p>
        <p style="font-size: 0.88rem; margin-bottom: 4px;">Điện thoại: <?= htmlspecialchars($order['customer_phone']) ?></p>
        <p style="font-size: 0.88rem; margin-bottom: 4px;">Email: <?= htmlspecialchars($order['customer_email']) ?></p>
        <p style="font-size: 0.88rem; margin-bottom: 0;">Địa chỉ: <?= htmlspecialchars($order['shipping_address']) ?></p>
        <?php if (!empty($order['notes'])): ?>
          <p style="font-size: 0.85rem; color: #64748b; margin-top: 8px; font-style: italic;">
            Ghi chú: <?= htmlspecialchars($order['notes']) ?>
          </p>
        <?php endif; ?>
      </div>

      <div style="background: #fafbfc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
          <i class="fa-solid fa-credit-card" style="color: #2563eb;"></i> Thông Tin Thanh Toán
        </h4>
        <p style="font-size: 0.88rem; margin-bottom: 4px;">
          Phương thức: <strong><?= Format::paymentMethodText($order['payment_method']) ?></strong>
        </p>
        <p style="font-size: 0.88rem; margin-bottom: 4px;">
          Trạng thái: <?= Format::paymentStatusBadge($order['payment_status']) ?>
        </p>
        <p style="font-size: 0.88rem; margin-bottom: 0;">
          Phí vận chuyển: Miễn phí nội thành Đà Nẵng
        </p>
      </div>
    </div>

    <!-- Items Table -->
    <h4 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 14px;">
      Danh Sách Sản Phẩm Đã Mua
    </h4>
    <div class="cart-table-wrap">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Sản Phẩm</th>
            <th style="text-align: right;">Đơn Giá</th>
            <th style="text-align: center;">Số Lượng</th>
            <th style="text-align: right;">Thành Tiền</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td>
                <div class="cart-item-info">
                  <?php if (!empty($item['thumbnail'])): ?>
                    <img src="<?= Url::upload($item['thumbnail'], 'products') ?>" alt="" class="cart-item-thumb">
                  <?php endif; ?>
                  <div>
                    <span style="font-size: 0.75rem; color: #64748b; font-family: monospace;">SKU: <?= htmlspecialchars($item['product_sku']) ?></span>
                    <h5 style="font-size: 0.92rem; font-weight: 600;">
                      <?= htmlspecialchars($item['product_name']) ?>
                    </h5>
                  </div>
                </div>
              </td>
              <td style="text-align: right; font-weight: 600;">
                <?= Format::currency($item['price']) ?>
              </td>
              <td style="text-align: center; font-weight: 600;">
                <?= $item['quantity'] ?>
              </td>
              <td style="text-align: right; font-weight: 700; color: #0f172a;">
                <?= Format::currency($item['total']) ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" style="text-align: right; font-weight: 600; padding: 14px 18px;">Tổng tiền hàng:</td>
            <td style="text-align: right; font-weight: 700;"><?= Format::currency($order['subtotal']) ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align: right; font-weight: 600; padding: 14px 18px;">Phí giao hàng:</td>
            <td style="text-align: right; color: #16a34a; font-weight: 700;">Miễn phí</td>
          </tr>
          <tr style="background-color: #fafbfc;">
            <td colspan="3" style="text-align: right; font-weight: 800; font-size: 1.1rem; padding: 16px 18px;">Tổng cộng đơn hàng:</td>
            <td style="text-align: right; font-weight: 900; font-size: 1.25rem; color: #dc2626;"><?= Format::currency($order['total_amount']) ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div style="margin-top: 20px;">
      <a href="<?= Url::to('account/orders') ?>" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách đơn hàng
      </a>
    </div>
  </div>
</div>
