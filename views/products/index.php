<?php
use App\Helpers\Format;
use App\Helpers\Url;

$selectedCat   = $filters['category_slug'] ?? '';
$selectedBrand = $filters['brand_slug'] ?? '';
$minPrice      = $filters['min_price'] ?? '';
$maxPrice      = $filters['max_price'] ?? '';
$inStock       = $filters['in_stock'] ?? '';
$sort          = $filters['sort'] ?? 'newest';
$search        = $filters['search'] ?? '';
?>

<div class="container">
  <!-- Breadcrumb -->
  <div style="margin: 18px 0 10px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <?php if ($activeCategory): ?>
      <a href="<?= Url::to('products') ?>">Sản phẩm</a>
      <span style="margin: 0 6px;">/</span>
      <span style="color: #0f172a; font-weight: 600;"><?= htmlspecialchars($activeCategory['name']) ?></span>
    <?php elseif (!empty($search)): ?>
      <a href="<?= Url::to('products') ?>">Sản phẩm</a>
      <span style="margin: 0 6px;">/</span>
      <span style="color: #0f172a; font-weight: 600;">Tìm kiếm: "<?= htmlspecialchars($search) ?>"</span>
    <?php else: ?>
      <span style="color: #0f172a; font-weight: 600;">Tất cả linh kiện &amp; phụ kiện PC</span>
    <?php endif; ?>
  </div>

  <div class="catalog-layout">
    <!-- Filter Sidebar -->
    <aside class="filter-sidebar">
      <form action="<?= Url::to('products') ?>" method="GET" id="catalog-filter-form">
        <?php if (!empty($search)): ?>
          <input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>">
        <?php endif; ?>

        <!-- Categories Filter -->
        <div class="filter-group">
          <div class="filter-title">
            <span><i class="fa-solid fa-layer-group"></i> Danh Mục</span>
          </div>
          <ul class="filter-list">
            <li class="filter-item">
              <label>
                <input type="radio" name="category" value="" <?= empty($selectedCat) ? 'checked' : '' ?> onchange="this.form.submit()">
                <span>Tất cả danh mục</span>
              </label>
            </li>
            <?php foreach ($categories as $cat): ?>
              <li class="filter-item">
                <label>
                  <input type="radio" name="category" value="<?= htmlspecialchars($cat['slug']) ?>" <?= $selectedCat === $cat['slug'] ? 'checked' : '' ?> onchange="this.form.submit()">
                  <span><?= htmlspecialchars($cat['name']) ?></span>
                </label>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Brands Filter -->
        <div class="filter-group">
          <div class="filter-title">
            <span><i class="fa-solid fa-copyright"></i> Hãng Sản Xuất</span>
          </div>
          <ul class="filter-list">
            <li class="filter-item">
              <label>
                <input type="radio" name="brand" value="" <?= empty($selectedBrand) ? 'checked' : '' ?> onchange="this.form.submit()">
                <span>Tất cả thương hiệu</span>
              </label>
            </li>
            <?php foreach ($brands as $b): ?>
              <li class="filter-item">
                <label>
                  <input type="radio" name="brand" value="<?= htmlspecialchars($b['slug']) ?>" <?= $selectedBrand === $b['slug'] ? 'checked' : '' ?> onchange="this.form.submit()">
                  <span><?= htmlspecialchars($b['name']) ?></span>
                </label>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Stock Availability Filter -->
        <div class="filter-group">
          <div class="filter-title">
            <span><i class="fa-solid fa-box-check"></i> Trạng Thái Kho</span>
          </div>
          <ul class="filter-list">
            <li class="filter-item">
              <label>
                <input type="radio" name="in_stock" value="" <?= $inStock === '' ? 'checked' : '' ?> onchange="this.form.submit()">
                <span>Tất cả</span>
              </label>
            </li>
            <li class="filter-item">
              <label>
                <input type="radio" name="in_stock" value="1" <?= $inStock === 1 || $inStock === '1' ? 'checked' : '' ?> onchange="this.form.submit()">
                <span>Chỉ còn hàng</span>
              </label>
            </li>
          </ul>
        </div>

        <!-- Price Range Filter -->
        <div class="filter-group">
          <div class="filter-title">
            <span><i class="fa-solid fa-tag"></i> Khoảng Giá (VNĐ)</span>
          </div>
          <div style="display: flex; gap: 8px; margin-bottom: 10px;">
            <input type="number" name="min_price" class="form-control" placeholder="Từ" value="<?= htmlspecialchars($minPrice) ?>" style="padding: 6px 10px; font-size: 0.85rem;">
            <input type="number" name="max_price" class="form-control" placeholder="Đến" value="<?= htmlspecialchars($maxPrice) ?>" style="padding: 6px 10px; font-size: 0.85rem;">
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 8px; font-size: 0.85rem;">
            Áp Dụng Lọc
          </button>
        </div>

        <!-- Reset Button -->
        <div style="margin-top: 14px;">
          <a href="<?= Url::to('products') ?>" class="btn btn-outline" style="width: 100%; padding: 8px; font-size: 0.85rem; text-align: center;">
            <i class="fa-solid fa-arrows-rotate"></i> Đặt lại bộ lọc
          </a>
        </div>
      </form>
    </aside>

    <!-- Products Content -->
    <section>
      <!-- Catalog Header Bar -->
      <div class="catalog-header">
        <div>
          <span style="font-weight: 700; color: #0f172a; font-size: 1.1rem;">
            <?php if ($activeCategory): ?>
              <?= htmlspecialchars($activeCategory['name']) ?>
            <?php else: ?>
              Danh Sách Sản Phẩm
            <?php endif; ?>
          </span>
          <span style="color: #64748b; font-size: 0.9rem; margin-left: 8px;">(<?= $totalProducts ?> sản phẩm)</span>
        </div>

        <!-- Sort Select -->
        <div style="display: flex; align-items: center; gap: 10px;">
          <label for="sort-select" style="font-size: 0.88rem; color: #64748b;">Sắp xếp theo:</label>
          <select id="sort-select" class="sort-select" onchange="location = this.value;">
            <?php
              $currentUrl = function($sortKey) use ($filters) {
                  $params = array_filter(array_merge($filters, ['sort' => $sortKey, 'page' => 1]));
                  return Url::to('products', $params);
              };
            ?>
            <option value="<?= $currentUrl('newest') ?>" <?= $sort === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
            <option value="<?= $currentUrl('price_asc') ?>" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
            <option value="<?= $currentUrl('price_desc') ?>" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
            <option value="<?= $currentUrl('name_asc') ?>" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Tên A - Z</option>
          </select>
        </div>
      </div>

      <!-- Product Grid or Empty State -->
      <?php if (!empty($products)): ?>
        <div class="product-grid" style="grid-template-columns: repeat(3, 1fr);">
          <?php foreach ($products as $product): ?>
            <?php include dirname(__DIR__) . '/products/_card.php'; ?>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a href="<?= Url::to('products', array_filter(array_merge($filters, ['page' => $page - 1]))) ?>" class="page-item">
                <i class="fa-solid fa-angle-left"></i>
              </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="<?= Url::to('products', array_filter(array_merge($filters, ['page' => $i]))) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
              <a href="<?= Url::to('products', array_filter(array_merge($filters, ['page' => $page + 1]))) ?>" class="page-item">
                <i class="fa-solid fa-angle-right"></i>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

      <?php else: ?>
        <div class="card-box" style="text-align: center; padding: 60px 20px;">
          <i class="fa-solid fa-magnifying-glass" style="font-size: 3rem; color: #94a3b8; margin-bottom: 16px;"></i>
          <h3 style="font-size: 1.25rem; margin-bottom: 8px;">Không tìm thấy sản phẩm nào</h3>
          <p style="color: #64748b; margin-bottom: 20px;">Không có linh kiện nào phù hợp với tiêu chí lọc hoặc từ khóa tìm kiếm của bạn.</p>
          <a href="<?= Url::to('products') ?>" class="btn btn-primary">
            <i class="fa-solid fa-arrows-rotate"></i> Xem Tất Cả Linh Kiện
          </a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>
