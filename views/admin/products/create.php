<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Thêm Mới Linh Kiện &amp; Phụ Kiện</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Điền đầy đủ thông tin kỹ thuật, giá bán và hình ảnh sản phẩm</span>
  </div>
  <a href="<?= Url::to('admin/products') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
  </a>
</div>

<form action="<?= Url::to('admin/products/store') ?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    <!-- Main Info Column -->
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Thông Tin Cơ Bản</h3>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label" for="name">Tên sản phẩm linh kiện <span style="color: #dc2626;">*</span></label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control" 
              placeholder="VD: CPU Intel Core i5-14600K (14 Nhân 20 Luồng, Up to 5.3GHz)..." 
              required 
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="sku">Mã sản phẩm / SKU <span style="color: #dc2626;">*</span></label>
              <input 
                type="text" 
                name="sku" 
                id="sku" 
                class="form-control" 
                placeholder="VD: CPU-INTEL-14600K" 
                required 
              />
            </div>

            <div class="form-group">
              <label class="form-label" for="slug">Đường dẫn thân thiện (Slug - Tùy chọn)</label>
              <input 
                type="text" 
                name="slug" 
                id="slug" 
                class="form-control" 
                placeholder="Để trống hệ thống sẽ tự sinh tự động" 
              />
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="category_id">Danh mục linh kiện <span style="color: #dc2626;">*</span></label>
              <select name="category_id" id="category_id" class="form-control" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="brand_id">Thương hiệu / Hãng sản xuất</label>
              <select name="brand_id" id="brand_id" class="form-control">
                <option value="">-- Chọn hãng --</option>
                <?php foreach ($brands as $b): ?>
                  <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="short_description">Mô tả ngắn sản phẩm</label>
            <textarea 
              name="short_description" 
              id="short_description" 
              class="form-control" 
              rows="3" 
              placeholder="Tóm tắt đặc điểm nổi bật..."
            ></textarea>
          </div>

          <div class="form-group">
            <label class="form-label" for="description">Bài viết mô tả chi tiết</label>
            <textarea 
              name="description" 
              id="description" 
              class="form-control" 
              rows="8" 
              placeholder="Nội dung giới thiệu chi tiết sản phẩm, công nghệ tích hợp..."
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Technical Specifications Card -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Thông Số Kỹ Thuật (Specs)</h3>
          <button type="button" class="btn btn-sm btn-primary" id="btn-add-spec">
            <i class="fa-solid fa-plus"></i> Thêm Thông Số
          </button>
        </div>
        <div class="admin-card-body" style="padding: 0;">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 45%;">Tên Thông Số</th>
                <th style="width: 45%;">Giá Trị</th>
                <th style="width: 10%; text-align: center;">Xóa</th>
              </tr>
            </thead>
            <tbody id="specs-table-body">
              <tr>
                <td><input type="text" name="spec_names[]" class="form-control" placeholder="VD: Socket / Dung lượng / Xung nhịp" value="Thương hiệu"></td>
                <td><input type="text" name="spec_values[]" class="form-control" placeholder="VD: LGA1700 / 32GB / 5.3GHz" value="Chính Hãng"></td>
                <td style="text-align: center;">
                  <button type="button" class="btn btn-sm btn-danger btn-remove-spec"><i class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td><input type="text" name="spec_names[]" class="form-control" placeholder="VD: Bảo hành" value="Bảo hành"></td>
                <td><input type="text" name="spec_values[]" class="form-control" placeholder="VD: 36 Tháng" value="36 Tháng"></td>
                <td style="text-align: center;">
                  <button type="button" class="btn btn-sm btn-danger btn-remove-spec"><i class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Sidebar Column: Price, Stock, Image, Status -->
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Giá Bán &amp; Kho Hàng</h3>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label" for="price">Giá bán niêm yết (VNĐ) <span style="color: #dc2626;">*</span></label>
            <input 
              type="number" 
              name="price" 
              id="price" 
              class="form-control" 
              placeholder="VD: 8500000" 
              min="0" 
              required 
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="sale_price">Giá khuyến mãi (VNĐ - Tùy chọn)</label>
            <input 
              type="number" 
              name="sale_price" 
              id="sale_price" 
              class="form-control" 
              placeholder="Để trống nếu không giảm giá" 
              min="0" 
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="stock_quantity">Số lượng tồn kho <span style="color: #dc2626;">*</span></label>
            <input 
              type="number" 
              name="stock_quantity" 
              id="stock_quantity" 
              class="form-control" 
              value="10" 
              min="0" 
              required 
            />
          </div>
        </div>
      </div>

      <!-- Thumbnail Upload -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Hình Ảnh Sản Phẩm</h3>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label" for="thumbnail">Chọn ảnh từ máy tính (JPG, PNG, WEBP)</label>
            <input 
              type="file" 
              name="thumbnail" 
              id="thumbnail" 
              class="form-control" 
              accept="image/jpeg,image/png,image/webp,image/gif" 
            />
          </div>

          <div style="text-align: center; margin-top: 14px;">
            <img 
              id="image-preview" 
              src="<?= Url::asset('images/placeholders/no-image.svg') ?>" 
              alt="Preview" 
              style="max-width: 160px; max-height: 160px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px;" 
            />
          </div>
        </div>
      </div>

      <!-- Visibility Status & Featured -->
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Cài Đặt Hiển Thị</h3>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" name="is_active" value="1" checked>
              <strong style="font-size: 0.9rem;">Kích hoạt hiển thị sản phẩm</strong>
            </label>
            <small style="color: #64748b; display: block; margin-left: 24px;">Sản phẩm sẽ xuất hiện trên trang chủ và danh mục bán lẻ.</small>
          </div>

          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" name="is_featured" value="1">
              <strong style="font-size: 0.9rem;">Đánh dấu sản phẩm nổi bật</strong>
            </label>
            <small style="color: #64748b; display: block; margin-left: 24px;">Hiển thị ở khu vực Sản phẩm nổi bật trên trang chủ.</small>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700; margin-top: 10px;">
            <i class="fa-solid fa-floppy-disk"></i> Lưu Sản Phẩm Mới
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
