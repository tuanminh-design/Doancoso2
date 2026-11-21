<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$isDiscounted = ($product['sale_price'] > 0 && $product['sale_price'] < $product['price']);
$currentPrice = $isDiscounted ? $product['sale_price'] : $product['price'];
$discountPct  = $isDiscounted ? Format::discountPercent($product['price'], $product['sale_price']) : null;
$inStock      = ($product['stock_quantity'] > 0);
$csrfToken    = Session::csrfToken();
?>

<div class="container">
  <!-- Breadcrumbs -->
  <div style="margin: 20px 0 10px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <a href="<?= Url::to('products', ['category' => $product['category_slug']]) ?>"><?= htmlspecialchars($product['category_name']) ?></a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;"><?= htmlspecialchars($product['name']) ?></span>
  </div>

  <!-- Product Main Info Card -->
  <div class="product-detail-layout">
    <!-- Gallery -->
    <div class="detail-gallery">
      <div class="main-image">
        <img 
          src="<?= Url::upload($product['thumbnail'], 'products') ?>" 
          alt="<?= htmlspecialchars($product['name']) ?>" 
          id="detail-main-img" 
        />
      </div>
      <div style="font-size: 0.8rem; color: #94a3b8; text-align: center;">
        <i class="fa-solid fa-camera"></i> Hình ảnh thực tế linh kiện chính hãng DaNang PC
      </div>
    </div>

    <!-- Product Purchasing Info -->
    <div class="detail-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>

      <div class="detail-meta">
        <span>Mã SP (SKU): <strong><?= htmlspecialchars($product['sku']) ?></strong></span>
        <span>Thương hiệu: <strong><?= htmlspecialchars($product['brand_name'] ?: 'Chính Hãng') ?></strong></span>
        <span>Danh mục: <strong><?= htmlspecialchars($product['category_name']) ?></strong></span>
        <span>Lượt xem: <?= number_format($product['view_count']) ?></span>
      </div>

      <!-- Pricing Box -->
      <div class="detail-price-box">
        <span class="detail-price-current"><?= Format::currency($currentPrice) ?></span>
        <?php if ($isDiscounted): ?>
          <span class="detail-price-original"><?= Format::currency($product['price']) ?></span>
          <span class="badge badge-discount" style="font-size: 0.85rem; padding: 4px 10px;">
            Tiết kiệm <?= $discountPct ?>%
          </span>
        <?php endif; ?>
      </div>

      <!-- Stock Status -->
      <div class="detail-stock-status">
        <span style="font-weight: 600; margin-right: 8px;">Tình trạng:</span>
        <?php if ($inStock): ?>
          <span class="badge in-stock" style="font-size: 0.85rem; padding: 4px 10px;">
            <i class="fa-solid fa-circle-check"></i> Còn hàng (Kho: <?= $product['stock_quantity'] ?> sản phẩm)
          </span>
        <?php else: ?>
          <span class="badge out-of-stock" style="font-size: 0.85rem; padding: 4px 10px;">
            <i class="fa-solid fa-circle-xmark"></i> Tạm hết hàng
          </span>
        <?php endif; ?>
      </div>

      <!-- Short Description -->
      <?php if (!empty($product['short_description'])): ?>
        <div style="margin-bottom: 24px; color: #475569; font-size: 0.95rem; line-height: 1.6;">
          <?= nl2br(htmlspecialchars($product['short_description'])) ?>
        </div>
      <?php endif; ?>

      <!-- Purchase Form -->
      <?php if ($inStock): ?>
        <form action="<?= Url::to('cart/add') ?>" method="POST">
          <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

          <div class="quantity-control">
            <span style="font-weight: 600; font-size: 0.95rem;">Số lượng:</span>
            <div class="qty-input-wrap">
              <button type="button" class="qty-btn" data-action="minus">-</button>
              <input 
                type="number" 
                name="quantity" 
                class="qty-input" 
                value="1" 
                min="1" 
                max="<?= $product['stock_quantity'] ?>" 
                readonly
              />
              <button type="button" class="qty-btn" data-action="plus">+</button>
            </div>
            <span style="font-size: 0.85rem; color: #64748b;">(Tối đa <?= $product['stock_quantity'] ?> món)</span>
          </div>

          <div style="display: flex; gap: 14px; margin-top: 10px;">
            <button type="submit" class="btn btn-primary" style="flex: 1; padding: 14px 20px; font-size: 1rem;">
              <i class="fa-solid fa-cart-shopping"></i> Thêm Vào Giỏ Hàng
            </button>
          </div>
        </form>
      <?php else: ?>
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; color: #991b1b; margin-top: 10px;">
          <i class="fa-solid fa-bell"></i> Sản phẩm hiện đang tạm hết hàng tại kho Đà Nẵng. Quý khách vui lòng liên hệ hotline <strong><?= htmlspecialchars($siteSettings['store_phone'] ?? '0905.123.456') ?></strong> để đặt trước linh kiện.
        </div>
      <?php endif; ?>

      <!-- Commitment Badges -->
      <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 0.85rem; color: #475569;">
        <div><i class="fa-solid fa-truck-fast" style="color: #2563eb; margin-right: 6px;"></i> Giao hàng hỏa tốc trong ngày tại Đà Nẵng</div>
        <div><i class="fa-solid fa-shield-check" style="color: #16a34a; margin-right: 6px;"></i> Bảo hành chính hãng 100% linh kiện</div>
        <div><i class="fa-solid fa-arrows-rotate" style="color: #d97706; margin-right: 6px;"></i> Lỗi 1 đổi 1 trong 30 ngày đầu</div>
        <div><i class="fa-solid fa-wrench" style="color: #0284c7; margin-right: 6px;"></i> Hỗ trợ kỹ thuật &amp; tra keo miễn phí</div>
      </div>
    </div>
  </div>

  <!-- Specifications & Description Tabs/Cards -->
  <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; margin-bottom: 50px;">
    <!-- Technical Specifications Table -->
    <div class="card-box">
      <h3 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-sliders" style="color: #2563eb;"></i> Thông Số Kỹ Thuật
      </h3>
      <?php if (!empty($specs)): ?>
        <table class="specs-table">
          <tbody>
            <?php foreach ($specs as $spec): ?>
              <tr>
                <td><?= htmlspecialchars($spec['spec_name']) ?></td>
                <td><?= htmlspecialchars($spec['spec_value']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="color: #94a3b8; font-style: italic;">Đang cập nhật bảng thông số chi tiết cho sản phẩm này.</p>
      <?php endif; ?>
    </div>

    <!-- Detailed Product Description -->
    <div class="card-box">
      <h3 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> Mô Tả Chi Tiết Sản Phẩm
      </h3>
      <div style="color: #334155; line-height: 1.7; font-size: 0.95rem;">
        <?php if (!empty($product['description'])): ?>
          <?= $product['description'] ?>
        <?php else: ?>
          <p>Linh kiện máy tính chính hãng, đầy đủ hóa đơn chứng từ và tem bảo hành chính thức tại Việt Nam. Vui lòng liên hệ showroom DaNang PC để được hỗ trợ kiểm tra tương thích phần cứng chi tiết.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Related Products (Same Category) -->
  <?php if (!empty($relatedProducts)): ?>
    <section class="section" style="margin-bottom: 60px;">
      <div class="section-header">
        <h2 class="section-title">
          <i class="fa-solid fa-boxes-stacked"></i> Sản Phẩm Cùng Danh Mục Liên Quan
        </h2>
        <a href="<?= Url::to('products', ['category' => $product['category_slug']]) ?>" class="section-more">
          Xem tất cả <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <div class="product-grid">
        <?php foreach ($relatedProducts as $product): ?>
          <?php include dirname(__DIR__) . '/products/_card.php'; ?>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</div>
