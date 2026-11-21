<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">
      Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['order_code']) ?>
    </h1>
    <span style="color: #64748b; font-size: 0.88rem;">Ngày tạo: <?= Format::datetime($order['created_at']) ?></span>
  </div>

  <a href="<?= Url::to('admin/orders') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
  </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
  <!-- Left Column: Items, Customer, History -->
  <div>
    <!-- Order Items -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Danh Sách Sản Phẩm (<?= count($items) ?> món)</h3>
      </div>
      <div class="admin-card-body" style="padding: 0;">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 50px;">Ảnh</th>
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
                  <img 
                    src="<?= Url::upload($item['thumbnail'], 'products') ?>" 
                    alt="" 
                    style="width: 44px; height: 44px; object-fit: contain; background: #f8fafc; border-radius: 4px;" 
                  />
                </td>
                <td>
                  <strong style="color: #0f172a; line-height: 1.3; display: block;">
                    <?= htmlspecialchars($item['product_name']) ?>
                  </strong>
                  <span style="font-size: 0.78rem; color: #64748b; font-family: monospace;">SKU: <?= htmlspecialchars($item['product_sku']) ?></span>
                </td>
                <td style="text-align: right; font-weight: 600;">
                  <?= Format::currency($item['price']) ?>
                </td>
                <td style="text-align: center; font-weight: 600;">
                  <?= $item['quantity'] ?>
                </td>
                <td style="text-align: right; font-weight: 700; color: #dc2626;">
                  <?= Format::currency($item['total']) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" style="text-align: right; font-weight: 600; padding: 12px 16px;">Tạm tính tiền hàng:</td>
              <td style="text-align: right; font-weight: 700;"><?= Format::currency($order['subtotal']) ?></td>
            </tr>
            <tr>
              <td colspan="4" style="text-align: right; font-weight: 600; padding: 12px 16px;">Phí vận chuyển:</td>
              <td style="text-align: right; color: #16a34a; font-weight: 700;">Miễn phí</td>
            </tr>
            <tr style="background-color: #fafbfc;">
              <td colspan="4" style="text-align: right; font-weight: 800; font-size: 1.05rem; padding: 14px 16px;">Tổng Giá Trị Đơn Hàng:</td>
              <td style="text-align: right; font-weight: 900; font-size: 1.25rem; color: #dc2626;"><?= Format::currency($order['total_amount']) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Customer & Delivery Info -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Thông Tin Giao Hàng &amp; Người Nhận</h3>
      </div>
      <div class="admin-card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div>
            <p style="margin-bottom: 8px;">Người nhận: <strong><?= htmlspecialchars($order['customer_name']) ?></strong></p>
            <p style="margin-bottom: 8px;">Số điện thoại: <strong><?= htmlspecialchars($order['customer_phone']) ?></strong></p>
            <p style="margin-bottom: 8px;">Email: <?= htmlspecialchars($order['customer_email']) ?></p>
          </div>
          <div>
            <p style="margin-bottom: 8px;">Địa chỉ giao hàng: <strong><?= htmlspecialchars($order['shipping_address']) ?></strong></p>
            <?php if (!empty($order['notes'])): ?>
              <p style="color: #d97706; margin-bottom: 0;">Ghi chú từ khách: <em>"<?= htmlspecialchars($order['notes']) ?>"</em></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Timeline History -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Lịch Sử Trạng Thái Đơn Hàng</h3>
      </div>
      <div class="admin-card-body">
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <?php foreach ($history as $h): ?>
            <div style="display: flex; gap: 14px; font-size: 0.88rem; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">
              <span style="color: #64748b; min-width: 140px;"><?= Format::datetime($h['created_at']) ?></span>
              <span style="font-weight: 700;"><?= Format::orderStatusBadge($h['status']) ?></span>
              <span style="color: #334155; flex: 1;"><?= htmlspecialchars($h['note'] ?? '') ?></span>
              <span style="color: #94a3b8; font-size: 0.8rem;">(<?= htmlspecialchars($h['created_by'] ?? 'Hệ thống') ?>)</span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Column: Status & Payment Controls -->
  <div>
    <!-- Update Order Status -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Cập Nhật Trạng Thái Đơn</h3>
      </div>
      <div class="admin-card-body">
        <form action="<?= Url::to('admin/orders/status/' . $order['id']) ?>" method="POST">
          <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

          <div class="form-group">
            <label class="form-label" for="status">Trạng thái xử lý:</label>
            <select name="status" id="status" class="form-control">
              <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
              <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
              <option value="shipping" <?= $order['status'] === 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
              <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
              <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy (Hoàn trả tồn kho)</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="note">Ghi chú cập nhật:</label>
            <input 
              type="text" 
              name="note" 
              id="note" 
              class="form-control" 
              placeholder="VD: Đã gọi điện chốt đơn, hàng đang giao..." 
            />
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fa-solid fa-arrows-rotate"></i> Cập Nhật Trạng Thái
          </button>
        </form>
      </div>
    </div>

    <!-- Update Payment Status -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">Trạng Thái Thanh Toán</h3>
      </div>
      <div class="admin-card-body">
        <form action="<?= Url::to('admin/orders/payment/' . $order['id']) ?>" method="POST">
          <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

          <div class="form-group">
            <label class="form-label">Phương thức:</label>
            <div><strong><?= Format::paymentMethodText($order['payment_method']) ?></strong></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="payment_status">Trạng thái thanh toán:</label>
            <select name="payment_status" id="payment_status" class="form-control">
              <option value="unpaid" <?= $order['payment_status'] === 'unpaid' ? 'selected' : '' ?>>Chưa thanh toán</option>
              <option value="paid" <?= $order['payment_status'] === 'paid' ? 'selected' : '' ?>>Đã thanh toán thành công</option>
            </select>
          </div>

          <button type="submit" class="btn btn-outline" style="width: 100%; font-weight: 600;">
            <i class="fa-solid fa-credit-card"></i> Cập Nhật Thanh Toán
          </button>
        </form>
      </div>
    </div>

    <!-- Direct Cancellation Action -->
    <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'completed'): ?>
      <div class="admin-card" style="border-color: #fecaca; background-color: #fff5f5;">
        <div class="admin-card-header" style="border-color: #fecaca;">
          <h3 class="admin-card-title" style="color: #991b1b;">Hủy Đơn Hàng &amp; Hoàn Kho</h3>
        </div>
        <div class="admin-card-body">
          <p style="font-size: 0.85rem; color: #991b1b; margin-bottom: 12px; line-height: 1.5;">
            Khi hủy đơn hàng, hệ thống sẽ tự động cộng lại đúng số lượng linh kiện vào kho tồn MySQL an toàn.
          </p>
          <form action="<?= Url::to('admin/orders/cancel/' . $order['id']) ?>" method="POST" class="form-delete" data-confirm="Bạn có chắc chắn muốn hủy đơn hàng này? Tồn kho của các linh kiện sẽ được tự động cộng trả lại vào kho!">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
            <button type="submit" class="btn btn-danger" style="width: 100%;">
              <i class="fa-solid fa-ban"></i> Hủy Đơn Hàng Này
            </button>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
