<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
$items = $cart['items'] ?? [];
$subtotal = $cart['subtotal'] ?? 0;
$total = $cart['total'] ?? 0;
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Giỏ Hàng Của Bạn</span>
  </div>

  <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 24px;">
    <i class="fa-solid fa-cart-shopping" style="color: #2563eb;"></i> Giỏ Hàng Mua Sắm
  </h1>

  <?php if (!empty($items)): ?>
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 30px; align-items: start;">
      <!-- Items Table -->
      <div>
        <div class="cart-table-wrap">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th style="text-align: right;">Đơn giá</th>
                <th style="text-align: center;">Số lượng</th>
                <th style="text-align: right;">Tạm tính</th>
                <th style="text-align: center;">Xóa</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
                <tr>
                  <td>
                    <div class="cart-item-info">
                      <img 
                        src="<?= Url::upload($item['thumbnail'], 'products') ?>" 
                        alt="<?= htmlspecialchars($item['name']) ?>" 
                        class="cart-item-thumb" 
                      />
                      <div>
                        <span style="font-size: 0.75rem; color: #64748b; font-family: monospace;">SKU: <?= htmlspecialchars($item['sku']) ?></span>
                        <h4 style="font-size: 0.92rem; font-weight: 600; line-height: 1.3;">
                          <a href="<?= Url::to('product/' . $item['slug']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                          </a>
                        </h4>
                        <?php if ($item['is_out_of_stock']): ?>
                          <span class="badge out-of-stock" style="margin-top: 4px;">Sản phẩm hiện đã hết hàng</span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: right; font-weight: 600; color: #0f172a;">
                    <?= Format::currency($item['unit_price']) ?>
                  </td>
                  <td style="text-align: center;">
                    <form action="<?= Url::to('cart/update') ?>" method="POST" class="cart-update-form" style="display: inline-flex;">
                      <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                      <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                      <div class="qty-input-wrap">
                        <button type="button" class="qty-btn" data-action="minus">-</button>
                        <input 
                          type="number" 
                          name="quantity" 
                          class="qty-input" 
                          value="<?= $item['quantity'] ?>" 
                          min="1" 
                          max="<?= $item['max_stock'] ?>" 
                          readonly
                        />
                        <button type="button" class="qty-btn" data-action="plus">+</button>
                      </div>
                    </form>
                  </td>
                  <td style="text-align: right; font-weight: 700; color: #dc2626;">
                    <?= Format::currency($item['subtotal']) ?>
                  </td>
                  <td style="text-align: center;">
                    <form action="<?= Url::to('cart/remove') ?>" method="POST" style="display: inline;">
                      <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                      <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                      <button type="submit" class="btn btn-outline btn-sm" style="color: #dc2626; border-color: #fecaca;" title="Xóa món này" data-confirm="Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
          <a href="<?= Url::to('products') ?>" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i> Tiếp tục chọn thêm linh kiện
          </a>

          <form action="<?= Url::to('cart/clear') ?>" method="POST" style="display: inline;">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
            <button type="submit" class="btn btn-outline" style="color: #dc2626;" data-confirm="Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng?">
              <i class="fa-solid fa-trash-can"></i> Xóa sạch giỏ hàng
            </button>
          </form>
        </div>
      </div>

      <!-- Order Summary Card -->
      <div class="card-box" style="position: sticky; top: 100px;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
          Tóm Tắt Đơn Hàng
        </h3>

        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.95rem; color: #475569;">
          <span>Tổng tiền hàng:</span>
          <strong><?= Format::currency($subtotal) ?></strong>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 0.95rem; color: #475569;">
          <span>Phí vận chuyển (Đà Nẵng):</span>
          <span style="color: #16a34a; font-weight: 700;">Miễn phí</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 24px; padding-top: 14px; border-top: 1px dashed #cbd5e1; font-size: 1.15rem; color: #0f172a;">
          <span style="font-weight: 700;">Tổng thanh toán:</span>
          <span style="font-size: 1.35rem; font-weight: 900; color: #dc2626;"><?= Format::currency($total) ?></span>
        </div>

        <a href="<?= Url::to('checkout') ?>" class="btn btn-accent" style="width: 100%; padding: 14px; font-size: 1rem; font-weight: 700;">
          <i class="fa-solid fa-credit-card"></i> Tiến Hành Đặt Hàng
        </a>

        <div style="margin-top: 18px; font-size: 0.8rem; color: #64748b; line-height: 1.5; text-align: center;">
          <i class="fa-solid fa-lock"></i> Đặt hàng an toàn, bảo mật thông tin tuyệt đối. Kiểm tra hàng trước khi thanh toán.
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- Empty Cart State -->
    <div class="card-box" style="text-align: center; padding: 70px 20px;">
      <i class="fa-solid fa-cart-shopping" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;"></i>
      <h2 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 10px;">Giỏ hàng của bạn đang trống</h2>
      <p style="color: #64748b; margin-bottom: 24px; max-width: 480px; margin-left: auto; margin-right: auto;">
        Hãy chọn các linh kiện máy tính, CPU, VGA, Mainboard, RAM, Bàn phím cơ hoặc Gaming Gear ưng ý để thêm vào giỏ hàng.
      </p>
      <a href="<?= Url::to('products') ?>" class="btn btn-primary" style="padding: 12px 28px; font-size: 1rem;">
        <i class="fa-solid fa-arrow-left"></i> Khám Phá Sản Phẩm Ngay
      </a>
    </div>
  <?php endif; ?>
</div>
