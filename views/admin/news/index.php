<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Quản Lý Tin Tức Công Nghệ</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Thị trường linh kiện, đánh giá CPU/GPU, cẩm nang build máy (Tổng: <?= $totalArticles ?>)</span>
  </div>

  <a href="<?= Url::to('admin/news/create') ?>" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Viết Bài Viết Mới
  </a>
</div>

<!-- Search -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
  <form action="<?= Url::to('admin/news') ?>" method="GET" style="display: flex; gap: 14px; align-items: center;">
    <div style="flex: 1;">
      <input 
        type="text" 
        name="q" 
        class="form-control" 
        placeholder="Tìm theo tiêu đề bài viết..." 
        value="<?= htmlspecialchars($search ?? '') ?>" 
      />
    </div>
    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
      <i class="fa-solid fa-magnifying-glass"></i> Tìm
    </button>
    <?php if (!empty($search)): ?>
      <a href="<?= Url::to('admin/news') ?>" class="btn btn-outline" style="padding: 10px 16px;">
        <i class="fa-solid fa-rotate-left"></i> Đặt lại
      </a>
    <?php endif; ?>
  </form>
</div>

<!-- Articles Table -->
<div class="admin-card">
  <div class="admin-card-body" style="padding: 0;">
    <?php if (!empty($articles)): ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 70px;">Ảnh</th>
            <th>Tiêu Đề Bài Viết</th>
            <th>Chuyên Mục</th>
            <th>Tác Giả</th>
            <th>Ngày Xuất Bản</th>
            <th style="text-align: center;">Lượt Xem</th>
            <th style="text-align: center;">Trạng Thái</th>
            <th style="text-align: center; width: 120px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($articles as $art): ?>
            <tr>
              <td>
                <img 
                  src="<?= Url::upload($art['thumbnail'], 'news') ?>" 
                  alt="" 
                  style="width: 60px; height: 42px; object-fit: cover; border-radius: 4px;" 
                />
              </td>
              <td>
                <strong style="color: #0f172a; line-height: 1.3; display: block;">
                  <a href="<?= Url::to('news/' . $art['slug']) ?>" target="_blank" style="color: inherit;">
                    <?= htmlspecialchars($art['title']) ?>
                  </a>
                </strong>
                <span style="font-size: 0.78rem; color: #64748b; font-family: monospace;">Slug: <?= htmlspecialchars($art['slug']) ?></span>
              </td>
              <td style="font-size: 0.88rem;">
                <?= htmlspecialchars($art['category_name'] ?: '---') ?>
              </td>
              <td style="font-size: 0.85rem; color: #475569;">
                <?= htmlspecialchars($art['author_name']) ?>
              </td>
              <td style="font-size: 0.82rem; color: #64748b;">
                <?= Format::date($art['published_at']) ?>
              </td>
              <td style="text-align: center; font-weight: 600;">
                <?= number_format($art['view_count']) ?>
              </td>
              <td style="text-align: center;">
                <?= $art['is_published'] ? '<span class="badge badge-success">Đã xuất bản</span>' : '<span class="badge badge-secondary">Bản nháp</span>' ?>
              </td>
              <td style="text-align: center;">
                <div style="display: inline-flex; gap: 8px;">
                  <a href="<?= Url::to('admin/news/edit/' . $art['id']) ?>" class="btn btn-sm btn-outline" title="Chỉnh sửa bài viết">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </a>
                  <form action="<?= Url::to('admin/news/delete/' . $art['id']) ?>" method="POST" class="form-delete" data-confirm="Bạn có chắc chắn muốn xóa bài viết '<?= htmlspecialchars(addslashes($art['title'])) ?>'?" style="display: inline;">
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
            <a href="<?= Url::to('admin/news', array_filter(['q' => $search, 'page' => $i])) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #64748b;">
        Không tìm thấy bài viết nào.
      </div>
    <?php endif; ?>
  </div>
</div>
