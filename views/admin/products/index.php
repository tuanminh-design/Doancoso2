<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
$search = $filters['search'] ?? '';
$selectedCategory = $filters['category_id'] ?? '';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Danh Sách Linh Kiện &amp; Phụ Kiện</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Tổng số: <strong><?= $totalProducts ?></strong> sản phẩm</span>
  </div>

  <a href="<?= Url::to('admin/products/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Thêm Sản Phẩm Mới
  </a>
</div>

<!-- Search & Category Filters -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
  <form action="<?= Url::to('admin/products') ?>" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
    <div style="flex: 1; min-width: 240px;">
      <input 
        type="text" 
        name="q" 
        class="form-control" 
        placeholder="Tìm theo tên sản phẩm hoặc mã SKU..." 
        value="<?= htmlspecialchars($search) ?>" 
      />
    </div>

    <div style="min-width: 200px;">
      <select name="category_id" class="form-control" onchange="this.form.submit()">
        <option value="">-- Tất cả danh mục --</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
      <i class="fa-solid fa-filter"></i> Lọc
    </button>

    <?php if (!empty($search) || !empty($selectedCategory)): ?>
      <a href="<?= Url::to('admin/products') ?>" class="btn btn-outline" style="padding: 10px 14px;">
        <i class="fa-solid fa-rotate-left"></i> Đặt lại
      </a>
    <?php endif; ?>
  </form>
</div>

<!-- Product Table -->
<div class="admin-card">
  <div class="admin-card-body" style="padding: 0;">
    <?php if (!empty($products)): ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 60px; text-align: center;">Ảnh</th>
            <th>Tên Sản Phẩm / Mã SKU</th>
            <th>Danh Mục</th>
            <th>Hãng</th>
            <th style="text-align: right;">Giá Bán</th>
            <th style="text-align: center;">Tồn Kho</th>
            <th style="text-align: center;">Trạng Thái</th>
            <th style="text-align: center; width: 140px;">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <tr>
              <td style="text-align: center;">
                <img 
                  src="<?= Url::upload($p['thumbnail'], 'products') ?>" 
                  alt="" 
                  style="width: 48px; height: 48px; object-fit: contain; background: #f8fafc; border-radius: 4px; padding: 2px;" 
                />
              </td>
              <td>
                <div style="font-weight: 600; color: #0f172a; line-height: 1.3;">
                  <a href="<?= Url::to('product/' . $p['slug']) ?>" target="_blank" style="color: inherit;">
                    <?= htmlspecialchars($p['name']) ?>
                  </a>
                </div>
                <div style="font-size: 0.78rem; color: #64748b; font-family: monospace;">
                  Mã: <?= htmlspecialchars($p['sku']) ?>
                  <?php if (!empty($p['is_featured'])): ?>
                    <span class="badge badge-primary" style="font-size: 0.68rem; margin-left: 6px;">Nổi bật</span>
                  <?php endif; ?>
                </div>
              </td>
              <td style="font-size: 0.88rem;">
                <?= htmlspecialchars($p['category_name'] ?: '---') ?>
              </td>
              <td style="font-size: 0.88rem;">
                <?= htmlspecialchars($p['brand_name'] ?: '---') ?>
              </td>
              <td style="text-align: right;">
                <?php if ($p['sale_price'] > 0 && $p['sale_price'] < $p['price']): ?>
                  <div style="font-weight: 700; color: #dc2626;"><?= Format::currency($p['sale_price']) ?></div>
                  <div style="font-size: 0.75rem; color: #94a3b8; text-decoration: line-through;"><?= Format::currency($p['price']) ?></div>
                <?php else: ?>
                  <div style="font-weight: 700; color: #0f172a;"><?= Format::currency($p['price']) ?></div>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <?php if ($p['stock_quantity'] > 0): ?>
                  <span class="badge badge-success"><?= $p['stock_quantity'] ?></span>
                <?php else: ?>
                  <span class="badge badge-danger">Hết hàng</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <?php if ($p['is_active']): ?>
                  <span class="badge badge-success">Hiển thị</span>
                <?php else: ?>
                  <span class="badge badge-secondary">Ẩn</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <div style="display: inline-flex; gap: 8px;">
                  <a href="<?= Url::to('admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline" title="Chỉnh sửa sản phẩm">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </a>
                  <form action="<?= Url::to('admin/products/delete/' . $p['id']) ?>" method="POST" class="form-delete" data-confirm="Bạn có chắc chắn muốn xóa sản phẩm '<?= htmlspecialchars(addslashes($p['name'])) ?>'?" style="display: inline;">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Xóa">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="pagination" style="padding: 20px 0;">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?= Url::to('admin/products', array_filter(array_merge($filters, ['page' => $i]))) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <div style="text-align: center; padding: 50px 20px; color: #64748b;">
        <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;"></i>
        <p>Không tìm thấy sản phẩm nào phù hợp.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
