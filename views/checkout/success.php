<?php
use App\Helpers\Format;
use App\Helpers\Url;

$isBankTransfer = ($order['payment_method'] === 'bank_transfer');
?>

<div class="container" style="margin: 40px auto 70px; max-width: 760px;">
  <div class="card-box" style="text-align: center; padding: 40px 30px; margin-bottom: 24px;">
    <div style="width: 72px; height: 72px; background-color: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
      <i class="fa-solid fa-circle-check"></i>
    </div>

    <h1 style="font-size: 1.7rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
      Đặt Hàng Thành Công!
    </h1>
    <p style="color: #64748b; font-size: 1rem; margin-bottom: 20px;">
      Cảm ơn bạn đã tin tưởng mua sắm tại <strong>DaNang PC</strong>. Đơn hàng của bạn đã được tiếp nhận và nhân viên tư vấn sẽ liên hệ để xác nhận giao hàng.
    </p>

    <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 14px 20px; display: inline-block; margin-bottom: 20px;">
      <span style="font-size: 0.9rem; color: #64748b;">Mã Đơn Hàng Của Bạn:</span>
      <strong style="font-size: 1.3rem; color: #2563eb; letter-spacing: 1px; display: block; margin-top: 4px;">
        <?= htmlspecialchars($order['order_code']) ?>
      </strong>
    </div>

    <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
      <a href="<?= Url::to('account/orders/' . $order['order_code']) ?>" class="btn btn-primary">
        <i class="fa-solid fa-clock-rotate-left"></i> Theo Dõi Đơn Hàng
      </a>
      <a href="<?= Url::to('/') ?>" class="btn btn-outline">
        <i class="fa-solid fa-house"></i> Về Trang Chủ
      </a>
    </div>
  </div>

  <!-- Bank Transfer Guide (If Selected) -->
  <?php if ($isBankTransfer): ?>
    <div class="card-box" style="background-color: #eff6ff; border-color: #bfdbfe; margin-bottom: 24px;">
      <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e40af; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-building-columns"></i> Hướng Dẫn Thanh Toán Chuyển Khoản Ngân Hàng
      </h3>
      <p style="color: #1e3a8a; font-size: 0.92rem; line-height: 1.5; margin-bottom: 14px;">
        Quý khách vui lòng chuyển khoản đúng số tiền <strong><?= Format::currency($order['total_amount']) ?></strong> vào tài khoản dưới đây để hoàn tất thủ tục thanh toán:
      </p>
      <div style="background-color: #ffffff; border-radius: 8px; padding: 16px; border: 1px solid #dbeafe;">
        <p style="margin-bottom: 6px; font-size: 0.92rem;">
          Ngân hàng: <strong><?= htmlspecialchars($settings['bank_name'] ?? 'Vietcombank') ?></strong>
        </p>
        <p style="margin-bottom: 6px; font-size: 0.92rem;">
          Số tài khoản: <strong style="font-size: 1.25rem; color: #2563eb; letter-spacing: 0.5px;"><?= htmlspecialchars($settings['bank_account_number'] ?? '10123456789') ?></strong>
        </p>
        <p style="margin-bottom: 6px; font-size: 0.92rem;">
          Chủ tài khoản: <strong><?= htmlspecialchars($settings['bank_account_name'] ?? 'CONG TY TNHH CONG NGHE DANANG PC') ?></strong>
        </p>
        <p style="margin-bottom: 6px; font-size: 0.92rem;">
          Chi nhánh: <strong><?= htmlspecialchars($settings['bank_branch'] ?? 'Nam Đà Nẵng') ?></strong>
        </p>
        <p style="margin-bottom: 0; font-size: 0.92rem; color: #dc2626;">
          Nội dung chuyển khoản (Bắt buộc): <strong style="background: #fef2f2; padding: 2px 6px; border-radius: 4px;"><?= htmlspecialchars($order['order_code']) ?></strong>
        </p>
      </div>
    </div>
  <?php endif; ?>

  <!-- Order Items & Shipping Summary -->
  <div class="card-box">
    <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
      Chi Tiết Sản Phẩm Đã Đặt
    </h3>

    <div style="margin-bottom: 20px;">
      <?php foreach ($items as $item): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
          <div>
            <h5 style="font-size: 0.92rem; font-weight: 600; color: #0f172a; margin-bottom: 2px;">
              <?= htmlspecialchars($item['product_name']) ?>
            </h5>
            <span style="font-size: 0.82rem; color: #64748b;">
              Mã: <?= htmlspecialchars($item['product_sku']) ?> &bull; Đơn giá: <?= Format::currency($item['price']) ?> x <?= $item['quantity'] ?>
            </span>
          </div>
          <div style="font-weight: 700; color: #0f172a;">
            <?= Format::currency($item['total']) ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Details Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 0.9rem; color: #475569;">
      <div>
        <strong style="display: block; color: #0f172a; margin-bottom: 4px;">Thông tin nhận hàng:</strong>
        <div>Người nhận: <strong><?= htmlspecialchars($order['customer_name']) ?></strong></div>
        <div>Số điện thoại: <?= htmlspecialchars($order['customer_phone']) ?></div>
        <div>Email: <?= htmlspecialchars($order['customer_email']) ?></div>
        <div>Địa chỉ: <?= htmlspecialchars($order['shipping_address']) ?></div>
      </div>
      <div>
        <strong style="display: block; color: #0f172a; margin-bottom: 4px;">Thanh toán &amp; Vận chuyển:</strong>
        <div>Phương thức: <?= Format::paymentMethodText($order['payment_method']) ?></div>
        <div>Trạng thái thanh toán: <?= Format::paymentStatusBadge($order['payment_status']) ?></div>
        <div>Phí vận chuyển: Miễn phí nội thành Đà Nẵng</div>
        <div style="margin-top: 8px; font-size: 1.1rem; color: #dc2626; font-weight: 800;">
          Tổng cộng: <?= Format::currency($order['total_amount']) ?>
        </div>
      </div>
    </div>
  </div>
</div>
