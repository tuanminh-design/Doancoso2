<?php
use App\Helpers\Format;
use App\Helpers\Url;
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <a href="<?= Url::to('news') ?>">Tin Tức Công Nghệ</a>
    <span style="margin: 0 6px;">/</span>
    <?php if (!empty($article['category_slug'])): ?>
      <a href="<?= Url::to('news', ['category' => $article['category_slug']]) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
      <span style="margin: 0 6px;">/</span>
    <?php endif; ?>
    <span style="color: #0f172a; font-weight: 600;"><?= htmlspecialchars(Format::excerpt($article['title'], 40)) ?></span>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 320px; gap: 36px; align-items: start;">
    <!-- Article Main Body -->
    <article class="card-box" style="padding: 36px;">
      <div class="news-meta" style="margin-bottom: 12px; font-size: 0.88rem;">
        <span><i class="fa-regular fa-calendar"></i> <?= Format::datetime($article['published_at']) ?></span>
        <span>&bull;</span>
        <span><i class="fa-solid fa-user-pen"></i> Tác giả: <strong><?= htmlspecialchars($article['author_name']) ?></strong></span>
        <?php if (!empty($article['category_name'])): ?>
          <span>&bull;</span>
          <span style="color: #2563eb; font-weight: 600;"><?= htmlspecialchars($article['category_name']) ?></span>
        <?php endif; ?>
        <span>&bull;</span>
        <span><i class="fa-regular fa-eye"></i> <?= number_format($article['view_count']) ?> lượt xem</span>
      </div>

      <h1 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.35; margin-bottom: 18px;">
        <?= htmlspecialchars($article['title']) ?>
      </h1>

      <!-- Summary Lead Box -->
      <div style="background-color: #f8fafc; border-left: 4px solid #2563eb; padding: 16px 20px; border-radius: 4px; font-size: 1.05rem; font-weight: 500; color: #334155; line-height: 1.6; margin-bottom: 24px;">
        <?= nl2br(htmlspecialchars($article['summary'])) ?>
      </div>

      <!-- Main Thumbnail -->
      <?php if (!empty($article['thumbnail'])): ?>
        <div style="margin-bottom: 28px; border-radius: 8px; overflow: hidden;">
          <img 
            src="<?= Url::upload($article['thumbnail'], 'news') ?>" 
            alt="<?= htmlspecialchars($article['title']) ?>" 
            style="width: 100%; max-height: 440px; object-fit: cover;" 
          />
        </div>
      <?php endif; ?>

      <!-- Full Rich Content -->
      <div style="color: #1e293b; line-height: 1.8; font-size: 1rem;" class="article-content">
        <?= $article['content'] ?>
      </div>

      <!-- Share & Back -->
      <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <a href="<?= Url::to('news') ?>" class="btn btn-outline">
          <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách tin tức
        </a>
        <div style="font-size: 0.88rem; color: #64748b;">
          Chia sẻ:
          <span style="font-size: 1.2rem; margin-left: 8px; color: #2563eb; cursor: pointer;"><i class="fa-brands fa-facebook"></i></span>
          <span style="font-size: 1.2rem; margin-left: 8px; color: #0284c7; cursor: pointer;"><i class="fa-brands fa-twitter"></i></span>
        </div>
      </div>
    </article>

    <!-- Sidebar Widget -->
    <aside>
      <!-- Categories Widget -->
      <div class="card-box" style="margin-bottom: 24px;">
        <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
          Chuyên Mục Tin Tức
        </h4>
        <ul style="list-style: none;">
          <?php foreach ($newsCategories as $nc): ?>
            <li style="margin-bottom: 8px;">
              <a href="<?= Url::to('news', ['category' => $nc['slug']]) ?>" style="display: flex; justify-content: space-between; font-size: 0.9rem; color: #475569;">
                <span><?= htmlspecialchars($nc['name']) ?></span>
                <span style="color: #94a3b8; font-size: 0.8rem;">(<?= $nc['news_count'] ?>)</span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Latest Articles -->
      <div class="card-box">
        <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
          Bài Viết Liên Quan Khác
        </h4>
        <div style="display: flex; flex-direction: column; gap: 14px;">
          <?php foreach ($latestArticles as $lat): ?>
            <?php if ($lat['id'] != $article['id']): ?>
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
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </aside>
  </div>
</div>
