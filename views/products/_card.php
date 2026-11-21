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
<div class="product-card">
  <!-- Badges -->
  <div class="product-badges">
    <?php if ($discountPct): ?>
      <span class="badge badge-discount">-<?= $discountPct ?>%</span>
    <?php endif; ?>
    <?php if (!empty($product['is_featured'])): ?>
      <span class="badge badge-featured">Nổi Bật</span>
    <?php endif; ?>
  </div>

  <!-- Stock Status Badge -->
  <span class="badge-stock <?= $inStock ? 'in-stock' : 'out-of-stock' ?>">
    <?= $inStock ? 'Còn hàng (' . $product['stock_quantity'] . ')' : 'Hết hàng' ?>
  </span>

  <!-- Thumbnail -->
  <a href="<?= Url::to('product/' . $product['slug']) ?>" class="product-thumb">
    <img 
      src="<?= Url::upload($product['thumbnail'], 'products') ?>" 
      alt="<?= htmlspecialchars($product['name']) ?>" 
      loading="lazy" 
    />
  </a>

  <!-- Info -->
  <div class="product-info">
    <span class="product-sku">Mã: <?= htmlspecialchars($product['sku']) ?></span>
    
    <h3 class="product-name">
      <a href="<?= Url::to('product/' . $product['slug']) ?>" title="<?= htmlspecialchars($product['name']) ?>">
        <?= htmlspecialchars($product['name']) ?>
      </a>
    </h3>

    <div class="product-price-box">
      <span class="price-current"><?= Format::currency($currentPrice) ?></span>
      <?php if ($isDiscounted): ?>
        <span class="price-original"><?= Format::currency($product['price']) ?></span>
      <?php endif; ?>
    </div>

    <!-- Buttons -->
    <div class="product-actions">
      <a href="<?= Url::to('product/' . $product['slug']) ?>" class="btn btn-outline" style="font-size: 0.82rem;">
        <i class="fa-regular fa-eye"></i> Chi tiết
      </a>

      <?php if ($inStock): ?>
        <form action="<?= Url::to('cart/add') ?>" method="POST" style="display: inline;">
          <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <input type="hidden" name="quantity" value="1">
          <button type="button" class="btn btn-cart btn-add-cart-ajax" title="Thêm vào giỏ hàng">
            <i class="fa-solid fa-cart-plus"></i>
          </button>
        </form>
      <?php else: ?>
        <button class="btn btn-outline btn-disabled" title="Tạm thời hết hàng" disabled>
          <i class="fa-solid fa-ban"></i>
        </button>
      <?php endif; ?>
    </div>
  </div>
</div>
