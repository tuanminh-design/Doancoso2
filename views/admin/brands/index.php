<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Quản Lý Thương Hiệu (Hãng)</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Intel, AMD, ASUS, MSI, Gigabyte, Corsair, Samsung...</span>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
  <!-- Create Brand Form -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Thêm Hãng Mới</h3>
    </div>
    <div class="admin-card-body">
      <form action="<?= Url::to('admin/brands/store') ?>" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="form-group">
          <label class="form-label" for="name">Tên thương hiệu <span style="color: #dc2626;">*</span></label>
          <input 
            type="text" 
            name="name" 
            id="name" 
            class="form-control" 
            placeholder="VD: NVIDIA" 
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

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700;">
          <i class="fa-solid fa-plus"></i> Thêm Thương Hiệu
        </button>
      </form>
    </div>
  </div>

  <!-- Brands Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Danh Sách Thương Hiệu Hiện Có</h3>
      <span style="font-size: 0.85rem; color: #64748b;"><?= count($brands) ?> thương hiệu</span>
    </div>
    <div class="admin-card-body" style="padding: 0;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Tên Thương Hiệu</th>
            <th>Slug</th>
            <th style="text-align: center;">Số Lượng SP</th>
            <th style="text-align: center; width: 100px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($brands as $b): ?>
            <tr>
              <td>
                <strong style="color: #0f172a; font-size: 0.95rem;"><?= htmlspecialchars($b['name']) ?></strong>
              </td>
              <td style="font-family: monospace; color: #64748b; font-size: 0.85rem;">
                <?= htmlspecialchars($b['slug']) ?>
              </td>
              <td style="text-align: center;">
                <span class="badge badge-primary"><?= $b['product_count'] ?> sản phẩm</span>
              </td>
              <td style="text-align: center;">
                <form action="<?= Url::to('admin/brands/delete/' . $b['id']) ?>" method="POST" class="form-delete" data-confirm="Bạn có chắc chắn muốn xóa thương hiệu '<?= htmlspecialchars(addslashes($b['name'])) ?>'?" style="display: inline;">
                  <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                  <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Xóa thương hiệu" <?= $b['product_count'] > 0 ? 'disabled title="Không thể xóa khi còn sản phẩm"' : '' ?>>
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
