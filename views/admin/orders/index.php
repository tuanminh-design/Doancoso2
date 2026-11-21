<?php
use App\Helpers\Format;
use App\Helpers\Url;

$search = $filters['search'] ?? '';
$selectedStatus = $filters['status'] ?? '';
$selectedPayment = $filters['payment_status'] ?? '';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Quản Lý Đơn Đặt Hàng</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Theo dõi, xử lý và cập nhật trạng thái đơn hàng của khách</span>
  </div>
</div>

<!-- Filters -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
  <form action="<?= Url::to('admin/orders') ?>" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
    <div style="flex: 1; min-width: 240px;">
      <input 
        type="text" 
        name="q" 
        class="form-control" 
        placeholder="Tìm theo mã đơn (ORD-...), tên, SĐT khách hàng..." 
        value="<?= htmlspecialchars($search) ?>" 
      />
    </div>

    <div style="min-width: 170px;">
      <select name="status" class="form-control" onchange="this.form.submit()">
        <option value="">-- Mọi trạng thái --</option>
        <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
        <option value="confirmed" <?= $selectedStatus === 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
        <option value="shipping" <?= $selectedStatus === 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
        <option value="completed" <?= $selectedStatus === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
        <option value="cancelled" <?= $selectedStatus === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
      </select>
    </div>

    <div style="min-width: 170px;">
      <select name="payment_status" class="form-control" onchange="this.form.submit()">
        <option value="">-- Mọi thanh toán --</option>
        <option value="unpaid" <?= $selectedPayment === 'unpaid' ? 'selected' : '' ?>>Chưa thanh toán</option>
        <option value="paid" <?= $selectedPayment === 'paid' ? 'selected' : '' ?>>Đã thanh toán</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
      <i class="fa-solid fa-filter"></i> Lọc
    </button>

    <?php if (!empty($search) || !empty($selectedStatus) || !empty($selectedPayment)): ?>
      <a href="<?= Url::to('admin/orders') ?>" class="btn btn-outline" style="padding: 10px 14px;">
        <i class="fa-solid fa-rotate-left"></i> Đặt lại
      </a>
    <?php endif; ?>
  </form>
</div>

<!-- Orders Table -->
<div class="admin-card">
  <div class="admin-card-body" style="padding: 0;">
    <?php if (!empty($orders)): ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Mã Đơn Hàng</th>
            <th>Khách Hàng / Liên Hệ</th>
            <th>Thời Gian Đặt</th>
            <th style="text-align: right;">Tổng Tiền</th>
            <th>Phương Thức</th>
            <th>Thanh Toán</th>
            <th>Trạng Thái</th>
            <th style="text-align: center; width: 100px;">Chi Tiết</th>
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
              <td>
                <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($order['customer_name']) ?></div>
                <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($order['customer_phone']) ?></div>
              </td>
              <td style="font-size: 0.82rem; color: #64748b;">
                <?= Format::datetime($order['created_at']) ?>
              </td>
              <td style="text-align: right; font-weight: 700; color: #dc2626;">
                <?= Format::currency($order['total_amount']) ?>
              </td>
              <td style="font-size: 0.85rem;">
                <?= $order['payment_method'] === 'cod' ? 'COD' : 'Chuyển Khoản' ?>
              </td>
              <td>
                <?= Format::paymentStatusBadge($order['payment_status']) ?>
              </td>
              <td>
                <?= Format::orderStatusBadge($order['status']) ?>
              </td>
              <td style="text-align: center;">
                <a href="<?= Url::to('admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-outline">
                  <i class="fa-regular fa-eye"></i> Xem
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="pagination" style="padding: 20px 0;">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?= Url::to('admin/orders', array_filter(array_merge($filters, ['page' => $i]))) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <div style="text-align: center; padding: 50px 20px; color: #64748b;">
        <i class="fa-solid fa-cart-shopping" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
        <p>Không tìm thấy đơn hàng nào phù hợp với bộ lọc.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
