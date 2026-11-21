<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Viết Bài Viết Công Nghệ Mới</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Chia sẻ kiến thức phần cứng, đánh giá linh kiện máy tính</span>
  </div>
  <a href="<?= Url::to('admin/news') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
  </a>
</div>

<form action="<?= Url::to('admin/news/store') ?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    <!-- Main Article Body -->
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Nội Dung Bài Viết</h3>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label" for="title">Tiêu đề bài viết <span style="color: #dc2626;">*</span></label>
            <input 
              type="text" 
              name="title" 
              id="title" 
              class="form-control" 
              placeholder="VD: Đánh giá hiệu năng card đồ họa RTX 4070 Super tại thị trường Đà Nẵng..." 
              required 
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="slug">Đường dẫn thân thiện (Slug)</label>
              <input 
                type="text" 
                name="slug" 
                id="slug" 
                class="form-control" 
                placeholder="Để trống hệ thống sẽ tự sinh" 
              />
            </div>

            <div class="form-group">
              <label class="form-label" for="category_id">Chuyên mục tin tức</label>
              <select name="category_id" id="category_id" class="form-control">
                <option value="">-- Chọn chuyên mục --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="summary">Tóm tắt ngắn (Lead) <span style="color: #dc2626;">*</span></label>
            <textarea 
              name="summary" 
              id="summary" 
              class="form-control" 
              rows="3" 
              placeholder="Đoạn văn ngắn tóm tắt nội dung chính để hiển thị ngoài danh sách bài viết..." 
              required
            ></textarea>
          </div>

          <div class="form-group">
            <label class="form-label" for="content">Nội dung chi tiết bài viết (Hỗ trợ HTML) <span style="color: #dc2626;">*</span></label>
            <textarea 
              name="content" 
              id="content" 
              class="form-control" 
              rows="12" 
              placeholder="<p>Nội dung bài viết chi tiết...</p><h3>1. Tiêu đề mục con</h3>" 
              required
            ></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Settings Column -->
    <div>
      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Hình Ảnh Đại Diện (Thumbnail)</h3>
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
              style="max-width: 100%; max-height: 160px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px;" 
            />
          </div>
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card-header">
          <h3 class="admin-card-title">Thiết Lập Xuất Bản</h3>
        </div>
        <div class="admin-card-body">
          <div class="form-group">
            <label class="form-label" for="author_name">Tên tác giả / Bút danh</label>
            <input 
              type="text" 
              name="author_name" 
              id="author_name" 
              class="form-control" 
              value="Ban Biên Tập DaNang PC" 
            />
          </div>

          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" name="is_published" value="1" checked>
              <strong style="font-size: 0.9rem;">Xuất bản công khai ngay</strong>
            </label>
            <small style="color: #64748b; display: block; margin-left: 24px;">Bài viết sẽ được hiển thị cho khách đọc.</small>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700; margin-top: 10px;">
            <i class="fa-solid fa-floppy-disk"></i> Lưu Bài Viết
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
