<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Quản Lý Danh Mục Linh Kiện</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Phân loại CPU, VGA, Mainboard, RAM, Ổ cứng, Gear...</span>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
  <!-- Create Category Form -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Thêm Danh Mục Mới</h3>
    </div>
    <div class="admin-card-body">
      <form action="<?= Url::to('admin/categories/store') ?>" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
          <label class="form-label" for="name">Tên danh mục <span style="color: #dc2626;">*</span></label>
          <input 
            type="text" 
            name="name" 
            id="name" 
            class="form-control" 
            placeholder="VD: Card Màn Hình (VGA)" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="slug">Đường dẫn thân thiện (Slug)</label>
          <input 
            type="text" 
            name="slug" 
            id="slug" 
            class="form-control" 
            placeholder="Để trống sẽ tự động tạo" 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="icon">Icon FontAwesome</label>
          <input 
            type="text" 
            name="icon" 
            id="icon" 
            class="form-control" 
            value="fa-microchip" 
            placeholder="fa-microchip, fa-desktop, fa-memory..." 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="sort_order">Thứ tự hiển thị</label>
          <input 
            type="number" 
            name="sort_order" 
            id="sort_order" 
            class="form-control" 
            value="1" 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Mô tả danh mục</label>
          <textarea 
            name="description" 
            id="description" 
            class="form-control" 
            rows="3" 
            placeholder="Mô tả danh mục linh kiện..."
          ></textarea>
        </div>

        <div class="form-group">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <input type="checkbox" name="is_active" value="1" checked>
            <span style="font-size: 0.9rem; font-weight: 600;">Kích hoạt danh mục</span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700;">
          <i class="fa-solid fa-plus"></i> Tạo Danh Mục
        </button>
      </form>
    </div>
  </div>

  <!-- Categories Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Danh Sách Danh Mục Hiện Có</h3>
      <span style="font-size: 0.85rem; color: #64748b;"><?= count($categories) ?> danh mục</span>
    </div>
    <div class="admin-card-body" style="padding: 0;">
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 50px; text-align: center;">Icon</th>
            <th>Tên Danh Mục / Slug</th>
            <th style="text-align: center;">Số Lượng SP</th>
            <th style="text-align: center;">Thứ Tự</th>
            <th style="text-align: center;">Trạng Thái</th>
            <th style="text-align: center; width: 100px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $cat): ?>
            <tr>
              <td style="text-align: center; font-size: 1.2rem; color: #2563eb;">
                <i class="fa-solid <?= htmlspecialchars($cat['icon'] ?: 'fa-microchip') ?>"></i>
              </td>
              <td>
                <strong style="color: #0f172a;"><?= htmlspecialchars($cat['name']) ?></strong>
                <div style="font-size: 0.78rem; color: #64748b; font-family: monospace;">
                  <?= htmlspecialchars($cat['slug']) ?>
                </div>
              </td>
              <td style="text-align: center;">
                <span class="badge badge-primary"><?= $cat['product_count'] ?> sản phẩm</span>
              </td>
              <td style="text-align: center; font-weight: 600;">
                <?= $cat['sort_order'] ?>
              </td>
              <td style="text-align: center;">
                <?= $cat['is_active'] ? '<span class="badge badge-success">Bật</span>' : '<span class="badge badge-secondary">Tắt</span>' ?>
              </td>
              <td style="text-align: center;">
                <form action="<?= Url::to('admin/categories/delete/' . $cat['id']) ?>" method="POST" class="form-delete" data-confirm="Bạn có chắc chắn muốn xóa danh mục '<?= htmlspecialchars(addslashes($cat['name'])) ?>'?" style="display: inline;">
                  <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                  <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Xóa danh mục" <?= $cat['product_count'] > 0 ? 'disabled title="Không thể xóa khi còn sản phẩm"' : '' ?>>
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
