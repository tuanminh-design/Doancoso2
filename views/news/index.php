<?php
use App\Helpers\Format;
use App\Helpers\Url;

$selectedCat = $filters['category_slug'] ?? '';
$search      = $filters['search'] ?? '';
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Tin Tức Công Nghệ &amp; Phần Cứng</span>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 300px; gap: 36px; align-items: start;">
    <!-- Main Articles List -->
    <div>
      <div style="margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
          <i class="fa-solid fa-newspaper" style="color: #2563eb;"></i> 
          <?= $activeCategory ? htmlspecialchars($activeCategory['name']) : 'Bản Tin Công Nghệ &amp; Thị Trường' ?>
        </h1>
        <span style="color: #64748b; font-size: 0.9rem;"><?= $totalArticles ?> bài viết</span>
      </div>

      <?php if (!empty($articles)): ?>
        <div style="display: flex; flex-direction: column; gap: 24px;">
          <?php foreach ($articles as $article): ?>
            <article class="card-box" style="padding: 0; overflow: hidden; display: grid; grid-template-columns: 260px 1fr; margin-bottom: 0;">
              <a href="<?= Url::to('news/' . $article['slug']) ?>" style="background-color: #f1f5f9; display: block; overflow: hidden;">
                <img 
                  src="<?= Url::upload($article['thumbnail'], 'news') ?>" 
                  alt="<?= htmlspecialchars($article['title']) ?>" 
                  style="width: 100%; height: 100%; object-fit: cover;" 
                />
              </a>
              <div style="padding: 20px; display: flex; flex-direction: column;">
                <div class="news-meta" style="margin-bottom: 8px;">
                  <span><i class="fa-regular fa-calendar"></i> <?= Format::date($article['published_at']) ?></span>
                  <?php if (!empty($article['category_name'])): ?>
                    <span>&bull;</span>
                    <span style="color: #2563eb; font-weight: 600;"><?= htmlspecialchars($article['category_name']) ?></span>
                  <?php endif; ?>
                  <span>&bull;</span>
                  <span><i class="fa-regular fa-eye"></i> <?= number_format($article['view_count']) ?></span>
                </div>

                <h2 style="font-size: 1.15rem; font-weight: 700; line-height: 1.4; margin-bottom: 10px;">
                  <a href="<?= Url::to('news/' . $article['slug']) ?>" style="color: #0f172a;">
                    <?= htmlspecialchars($article['title']) ?>
                  </a>
                </h2>

                <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6; margin-bottom: 16px; flex: 1;">
                  <?= htmlspecialchars(Format::excerpt($article['summary'], 160)) ?>
                </p>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; font-size: 0.85rem;">
                  <span style="color: #94a3b8;"><i class="fa-solid fa-user-pen"></i> <?= htmlspecialchars($article['author_name']) ?></span>
                  <a href="<?= Url::to('news/' . $article['slug']) ?>" class="news-read-more">
                    <span>Xem chi tiết</span> <i class="fa-solid fa-arrow-right"></i>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="<?= Url::to('news', array_filter(array_merge($filters, ['page' => $i]))) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>
          </div>
        <?php endif; ?>

      <?php else: ?>
        <div class="card-box" style="text-align: center; padding: 60px 20px;">
          <i class="fa-solid fa-newspaper" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px;"></i>
          <h3 style="font-size: 1.2rem;">Chưa có bài viết nào</h3>
          <p style="color: #64748b;">Nội dung đang được ban biên tập DaNang PC cập nhật.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar Widget -->
    <aside>
      <!-- Search Form -->
      <div class="card-box" style="margin-bottom: 24px;">
        <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
          Tìm Kiếm Bài Viết
        </h4>
        <form action="<?= Url::to('news') ?>" method="GET">
          <div style="display: flex; gap: 6px;">
            <input 
              type="text" 
              name="q" 
              class="form-control" 
              placeholder="Từ khóa..." 
              value="<?= htmlspecialchars($search) ?>" 
              required
            />
            <button type="submit" class="btn btn-primary" style="padding: 0 16px;">
              <i class="fa-solid fa-magnifying-glass"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- Categories -->
      <div class="card-box" style="margin-bottom: 24px;">
        <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
          Chuyên Mục Tin Tức
        </h4>
        <ul style="list-style: none;">
          <li style="margin-bottom: 8px;">
            <a href="<?= Url::to('news') ?>" style="display: flex; justify-content: space-between; font-size: 0.9rem; color: <?= empty($selectedCat) ? '#2563eb; font-weight: bold;' : '#475569;' ?>">
              <span>Tất cả bài viết</span>
            </a>
          </li>
          <?php foreach ($newsCategories as $nc): ?>
            <li style="margin-bottom: 8px;">
              <a href="<?= Url::to('news', ['category' => $nc['slug']]) ?>" style="display: flex; justify-content: space-between; font-size: 0.9rem; color: <?= $selectedCat === $nc['slug'] ? '#2563eb; font-weight: bold;' : '#475569;' ?>">
                <span><?= htmlspecialchars($nc['name']) ?></span>
                <span style="color: #94a3b8; font-size: 0.8rem;">(<?= $nc['news_count'] ?>)</span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Latest Articles Widget -->
      <div class="card-box">
        <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
          Bài Viết Mới Nhất
        </h4>
        <div style="display: flex; flex-direction: column; gap: 14px;">
          <?php foreach ($latestArticles as $lat): ?>
            <div style="display: flex; gap: 10px; align-items: center;">
              <img 
                src="<?= Url::upload($lat['thumbnail'], 'news') ?>" 
                alt="" 
                style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px;" 
              />
              <div style="flex: 1; min-width: 0;">
                <h5 style="font-size: 0.85rem; font-weight: 600; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                  <a href="<?= Url::to('news/' . $lat['slug']) ?>">
                    <?= htmlspecialchars($lat['title']) ?>
                  </a>
                </h5>
                <span style="font-size: 0.75rem; color: #94a3b8;"><?= Format::date($lat['published_at']) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>
  </div>
</div>
