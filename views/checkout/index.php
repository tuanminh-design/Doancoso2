<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
$items     = $cart['items'] ?? [];
$subtotal  = $cart['subtotal'] ?? 0;
$total     = $cart['total'] ?? 0;
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <a href="<?= Url::to('cart') ?>">Giỏ Hàng</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Thanh Toán Đơn Hàng</span>
  </div>

  <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 24px;">
    <i class="fa-solid fa-credit-card" style="color: #2563eb;"></i> Thông Tin Thanh Toán &amp; Giao Hàng
  </h1>

  <form action="<?= Url::to('checkout/process') ?>" method="POST" id="checkout-form">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

    <div class="checkout-grid">
      <!-- Left Column: Shipping Information -->
      <div>
        <div class="card-box">
          <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-location-dot" style="color: #2563eb;"></i> 1. Thông Tin Người Nhận Hàng
          </h3>

          <div class="form-group">
            <label class="form-label" for="customer_name">Họ và tên người nhận <span style="color: #dc2626;">*</span></label>
            <input 
              type="text" 
              name="customer_name" 
              id="customer_name" 
              class="form-control" 
              placeholder="Ví dụ: Nguyễn Văn An" 
              value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
              required
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="customer_phone">Số điện thoại liên hệ <span style="color: #dc2626;">*</span></label>
              <input 
                type="tel" 
                name="customer_phone" 
                id="customer_phone" 
                class="form-control" 
                placeholder="Ví dụ: 0905123456" 
                value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                required
              />
            </div>

            <div class="form-group">
              <label class="form-label" for="customer_email">Địa chỉ Email <span style="color: #dc2626;">*</span></label>
              <input 
                type="email" 
                name="customer_email" 
                id="customer_email" 
                class="form-control" 
                placeholder="Ví dụ: email@gmail.com" 
                value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="shipping_address">Địa chỉ nhận hàng tại Đà Nẵng (hoặc toàn quốc) <span style="color: #dc2626;">*</span></label>
            <textarea 
              name="shipping_address" 
              id="shipping_address" 
              class="form-control" 
              rows="3" 
              placeholder="Số nhà, tên đường, phường/xã, quận/huyện, TP. Đà Nẵng..." 
              required
            ><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
          </div>

          <div class="form-group">
            <label class="form-label" for="notes">Ghi chú đơn hàng (Tùy chọn)</label>
            <textarea 
              name="notes" 
              id="notes" 
              class="form-control" 
              rows="2" 
              placeholder="Giao hàng giờ hành chính, gọi điện trước khi đến..."
            ></textarea>
          </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="card-box">
          <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-money-check-dollar" style="color: #2563eb;"></i> 2. Chọn Phương Thức Thanh Toán
          </h3>

          <div style="display: flex; flex-direction: column; gap: 14px;">
            <!-- COD -->
            <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #fafbfc;">
              <input type="radio" name="payment_method" value="cod" checked style="margin-top: 4px;">
              <div>
                <strong style="color: #0f172a; display: block;">Thanh toán khi nhận hàng (COD)</strong>
                <span style="font-size: 0.85rem; color: #64748b;">Khách hàng thanh toán tiền mặt trực tiếp cho nhân viên giao hàng khi nhận và kiểm tra linh kiện.</span>
              </div>
            </label>

            <!-- Bank Transfer -->
            <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; background: #fafbfc;">
              <input type="radio" name="payment_method" value="bank_transfer" style="margin-top: 4px;">
              <div>
                <strong style="color: #0f172a; display: block;">Chuyển khoản qua Ngân Hàng (Vietcombank / QR Code)</strong>
                <span style="font-size: 0.85rem; color: #64748b;">Chuyển khoản online qua ứng dụng ngân hàng hoặc quét mã QR 24/7. Đơn hàng sẽ được xác nhận tự động.</span>
              </div>
            </label>

            <!-- Dynamic Bank Info Box -->
            <div id="bank-transfer-info" style="display: none; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 18px; margin-top: 6px;">
              <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e40af; margin-bottom: 8px;">
                <i class="fa-solid fa-building-columns"></i> Thông Tin Tài Khoản Ngân Hàng
              </h4>
              <p style="font-size: 0.88rem; color: #1e3a8a; margin-bottom: 6px;">
                Ngân hàng: <strong><?= htmlspecialchars($siteSettings['bank_name'] ?? 'Vietcombank') ?></strong>
              </p>
              <p style="font-size: 0.88rem; color: #1e3a8a; margin-bottom: 6px;">
                Số tài khoản: <strong style="font-size: 1.1rem; color: #2563eb;"><?= htmlspecialchars($siteSettings['bank_account_number'] ?? '10123456789') ?></strong>
              </p>
              <p style="font-size: 0.88rem; color: #1e3a8a; margin-bottom: 6px;">
                Chủ tài khoản: <strong><?= htmlspecialchars($siteSettings['bank_account_name'] ?? 'CONG TY TNHH CONG NGHE DANANG PC') ?></strong>
              </p>
              <p style="font-size: 0.88rem; color: #1e3a8a; margin-bottom: 0;">
                Chi nhánh: <?= htmlspecialchars($siteSettings['bank_branch'] ?? 'Chi nhánh Nam Đà Nẵng') ?>
              </p>
              <small style="display: block; margin-top: 10px; color: #64748b; font-style: italic;">
                * Nội dung chuyển khoản sẽ là: Mã đơn hàng (được hiển thị ngay sau khi bấm Xác nhận).
              </small>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Order Products Summary -->
      <div>
        <div class="card-box" style="position: sticky; top: 100px;">
          <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
            Đơn Hàng Của Bạn (<?= count($items) ?> món)
          </h3>

          <div style="max-height: 300px; overflow-y: auto; margin-bottom: 16px;">
            <?php foreach ($items as $it): ?>
              <div style="display: flex; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; align-items: center;">
                <img 
                  src="<?= Url::upload($it['thumbnail'], 'products') ?>" 
                  alt="<?= htmlspecialchars($it['name']) ?>" 
                  style="width: 44px; height: 44px; object-fit: contain; background: #f8fafc; border-radius: 4px;" 
                />
                <div style="flex: 1; min-width: 0;">
                  <h5 style="font-size: 0.85rem; font-weight: 600; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <?= htmlspecialchars($it['name']) ?>
                  </h5>
                  <span style="font-size: 0.8rem; color: #64748b;">
                    <?= Format::currency($it['unit_price']) ?> x <?= $it['quantity'] ?>
                  </span>
                </div>
                <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a;">
                  <?= Format::currency($it['subtotal']) ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.92rem; color: #475569;">
            <span>Tiền hàng:</span>
            <strong><?= Format::currency($subtotal) ?></strong>
          </div>

          <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 0.92rem; color: #475569;">
            <span>Phí vận chuyển:</span>
            <span style="color: #16a34a; font-weight: 700;">Miễn phí nội thành</span>
          </div>

          <div style="display: flex; justify-content: space-between; margin-bottom: 24px; padding-top: 14px; border-top: 1px dashed #cbd5e1; font-size: 1.1rem; color: #0f172a;">
            <span style="font-weight: 700;">Tổng thanh toán:</span>
            <span style="font-size: 1.35rem; font-weight: 900; color: #dc2626;"><?= Format::currency($total) ?></span>
          </div>

          <button type="submit" class="btn btn-accent" id="btn-submit-order" style="width: 100%; padding: 14px; font-size: 1.05rem; font-weight: 700;">
            <i class="fa-solid fa-check"></i> Xác Nhận Đặt Hàng
          </button>

          <p style="font-size: 0.78rem; color: #94a3b8; text-align: center; margin-top: 14px; line-height: 1.4;">
            Nhấp "Xác Nhận Đặt Hàng" đồng nghĩa với việc bạn đồng ý với Điều khoản dịch vụ và Chính sách bảo hành của DaNang PC.
          </p>
        </div>
      </div>
    </div>
  </form>
</div>
