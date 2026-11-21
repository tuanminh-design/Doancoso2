<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Chỉnh Sửa Bài Viết: <?= htmlspecialchars($article['title']) ?></h1>
    <span style="color: #64748b; font-size: 0.88rem;">Cập nhật tiêu đề, nội dung hoặc thay đổi ảnh đại diện</span>
  </div>
  <a href="<?= Url::to('admin/news') ?>" class="btn btn-outline">
    <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
  </a>
</div>

<form action="<?= Url::to('admin/news/update/' . $article['id']) ?>" method="POST" enctype="multipart/form-data">
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
              value="<?= htmlspecialchars($article['title']) ?>" 
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
                value="<?= htmlspecialchars($article['slug']) ?>" 
              />
            </div>

            <div class="form-group">
              <label class="form-label" for="category_id">Chuyên mục tin tức</label>
              <select name="category_id" id="category_id" class="form-control">
                <option value="">-- Chọn chuyên mục --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= $article['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                  </option>
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
              required
            ><?= htmlspecialchars($article['summary']) ?></textarea>
          </div>

          <div class="form-group">
            <label class="form-label" for="content">Nội dung chi tiết bài viết (Hỗ trợ HTML) <span style="color: #dc2626;">*</span></label>
            <textarea 
              name="content" 
              id="content" 
              class="form-control" 
              rows="12" 
              required
            ><?= htmlspecialchars($article['content']) ?></textarea>
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
            <label class="form-label" for="thumbnail">Thay đổi ảnh đại diện (Tùy chọn)</label>
            <input 
              type="file" 
              name="thumbnail" 
              id="thumbnail" 
              class="form-control" 
              accept="image/jpeg,image/png,image/webp,image/gif" 
            />
          </div>

          <div style="text-align: center; margin-top: 14px;">
            <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 6px;">Ảnh hiện tại:</p>
            <img 
              id="image-preview" 
              src="<?= Url::upload($article['thumbnail'], 'news') ?>" 
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
              value="<?= htmlspecialchars($article['author_name']) ?>" 
            />
          </div>

          <div class="form-group">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" name="is_published" value="1" <?= !empty($article['is_published']) ? 'checked' : '' ?>>
              <strong style="font-size: 0.9rem;">Xuất bản công khai bài viết</strong>
            </label>
            <small style="color: #64748b; display: block; margin-left: 24px;">Bài viết sẽ được hiển thị cho khách đọc.</small>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: 700; margin-top: 10px;">
            <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi Bài Viết
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
