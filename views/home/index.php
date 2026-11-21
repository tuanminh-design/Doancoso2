<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$siteName = $siteSettings['site_name'] ?? 'DaNang PC';
$csrfToken = Session::csrfToken();
?>

<!-- 1. Hero Banner (Single Attractive Technology Store Hero) -->
<section class="hero-banner">
  <div class="container">
    <div class="hero-wrapper">
      <div class="hero-content">
        <div class="hero-badges">
          <div class="hero-badge-item">
            <i class="fa-solid fa-certificate"></i> 100% Chính Hãng
          </div>
          <div class="hero-badge-item">
            <i class="fa-solid fa-truck-fast"></i> Giao Hỏa Tốc Đà Nẵng
          </div>
          <div class="hero-badge-item">
            <i class="fa-solid fa-rotate-left"></i> 1 Đổi 1 Trong 30 Ngày
          </div>
        </div>
        <h1>Siêu Thị Linh Kiện &amp; Phụ Kiện Máy Tính Đà Nẵng</h1>
        <p>
          Chuyên cung cấp CPU, VGA, Mainboard, RAM, Ổ cứng SSD, Nguồn, Case, Màn hình và Gaming Gear chính hãng từ các thương hiệu hàng đầu thế giới: Intel, AMD, ASUS, MSI, Gigabyte, Corsair.
        </p>
        <a href="<?= Url::to('products') ?>" class="hero-cta">
          <span>Khám Phá Sản Phẩm Ngay</span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <div class="hero-graphic">
        <div class="hero-card-preview">
          <i class="fa-solid fa-microchip" style="font-size: 3rem; color: #38bdf8; margin-bottom: 12px;"></i>
          <h3>Tư Vấn &amp; Tối Ưu Cấu Hình</h3>
          <p>Lắp ráp dàn máy theo yêu cầu đồ họa 3D, kiến trúc, văn phòng và Gaming chuẩn eSports.</p>
          <div class="hero-specs-list">
            <div class="hero-spec-item">
              <strong>14+</strong>
              <span>Danh Mục</span>
            </div>
            <div class="hero-spec-item">
              <strong>36T</strong>
              <span>Bảo Hành</span>
            </div>
            <div class="hero-spec-item">
              <strong>0đ</strong>
              <span>Phí Vận Chuyển</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 2. Product Categories Grid -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fa-solid fa-layer-group"></i> Danh Mục Linh Kiện &amp; Phụ Kiện
      </h2>
      <a href="<?= Url::to('products') ?>" class="section-more">
        Xem tất cả danh mục <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="category-grid">
      <?php if (!empty($navCategories)): ?>
        <?php foreach ($navCategories as $cat): ?>
          <a href="<?= Url::to('products', ['category' => $cat['slug']]) ?>" class="category-card">
            <div class="category-card-icon">
              <i class="fa-solid <?= htmlspecialchars($cat['icon'] ?: 'fa-microchip') ?>"></i>
            </div>
            <span class="category-card-name"><?= htmlspecialchars($cat['name']) ?></span>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 3. Section: Featured Products (Sản Phẩm Nổi Bật) -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fa-solid fa-star"></i> Linh Kiện Máy Tính Nổi Bật
      </h2>
      <a href="<?= Url::to('products') ?>" class="section-more">
        Xem tất cả <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="product-grid">
      <?php if (!empty($featuredProducts)): ?>
        <?php foreach ($featuredProducts as $product): ?>
          <?php include dirname(__DIR__) . '/products/_card.php'; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 4. Section: Products On Sale (Khuyến Mãi & Giảm Giá) -->
<?php if (!empty($onSaleProducts)): ?>
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title title-sale">
        <i class="fa-solid fa-fire"></i> Sản Phẩm Giảm Giá Sốc
      </h2>
      <a href="<?= Url::to('products', ['sort' => 'price_asc']) ?>" class="section-more">
        Xem ưu đãi <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="product-grid">
      <?php foreach ($onSaleProducts as $product): ?>
        <?php include dirname(__DIR__) . '/products/_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- 5. Section: Best Sellers (Sản Phẩm Bán Chạy) -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fa-solid fa-chart-line"></i> Sản Phẩm Bán Chạy Nhất
      </h2>
      <a href="<?= Url::to('products') ?>" class="section-more">
        Xem thêm <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="product-grid">
      <?php if (!empty($bestSellers)): ?>
        <?php foreach ($bestSellers as $product): ?>
          <?php include dirname(__DIR__) . '/products/_card.php'; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 6. Section: New Arrivals (Sản Phẩm Mới Về) -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fa-solid fa-sparkles"></i> Linh Kiện Mới Về
      </h2>
      <a href="<?= Url::to('products', ['sort' => 'newest']) ?>" class="section-more">
        Xem mới nhất <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="product-grid">
      <?php if (!empty($newArrivals)): ?>
        <?php foreach ($newArrivals as $product): ?>
          <?php include dirname(__DIR__) . '/products/_card.php'; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 7. Section: Technology News (Tin Tức Công Nghệ & Phần Cứng) -->
<section class="section" style="margin-bottom: 60px;">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fa-solid fa-newspaper"></i> Tin Tức Công Nghệ &amp; Thị Trường Phần Cứng
      </h2>
      <a href="<?= Url::to('news') ?>" class="section-more">
        Xem thêm tin tức <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>

    <div class="news-grid">
      <?php if (!empty($latestNews)): ?>
        <?php foreach ($latestNews as $article): ?>
          <article class="news-card">
            <a href="<?= Url::to('news/' . $article['slug']) ?>" class="news-thumb">
              <img src="<?= Url::upload($article['thumbnail'], 'news') ?>" alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy" />
            </a>
            <div class="news-content">
              <div class="news-meta">
                <span><i class="fa-regular fa-calendar"></i> <?= Format::date($article['published_at']) ?></span>
                <?php if (!empty($article['category_name'])): ?>
                  <span>&bull;</span>
                  <span><?= htmlspecialchars($article['category_name']) ?></span>
                <?php endif; ?>
              </div>
              <h3 class="news-title">
                <a href="<?= Url::to('news/' . $article['slug']) ?>">
                  <?= htmlspecialchars($article['title']) ?>
                </a>
              </h3>
              <p class="news-excerpt">
                <?= htmlspecialchars(Format::excerpt($article['summary'], 110)) ?>
              </p>
              <a href="<?= Url::to('news/' . $article['slug']) ?>" class="news-read-more">
                <span>Đọc bài viết</span>
                <i class="fa-solid fa-arrow-right"></i>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
