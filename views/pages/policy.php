<?php
use App\Helpers\Url;
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;"><?= htmlspecialchars($policyTitle) ?></span>
  </div>

  <div style="display: grid; grid-template-columns: 280px 1fr; gap: 36px; align-items: start;">
    <!-- Policy Navigation Sidebar -->
    <aside class="card-box" style="padding: 14px 0;">
      <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; padding: 0 20px 12px; border-bottom: 1px solid #e2e8f0;">
        Chính Sách Khách Hàng
      </h3>
      <ul style="list-style: none;">
        <li>
          <a href="<?= Url::to('policy/warranty') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-size: 0.9rem; color: <?= $activeType === 'warranty' ? '#2563eb; font-weight: 700; background: #eff6ff;' : '#475569;' ?>">
            <i class="fa-solid fa-shield-halved"></i> Chính sách bảo hành
          </a>
        </li>
        <li>
          <a href="<?= Url::to('policy/shipping') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-size: 0.9rem; color: <?= $activeType === 'shipping' ? '#2563eb; font-weight: 700; background: #eff6ff;' : '#475569;' ?>">
            <i class="fa-solid fa-truck-fast"></i> Chính sách vận chuyển
          </a>
        </li>
        <li>
          <a href="<?= Url::to('policy/return') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-size: 0.9rem; color: <?= $activeType === 'return' ? '#2563eb; font-weight: 700; background: #eff6ff;' : '#475569;' ?>">
            <i class="fa-solid fa-rotate-left"></i> Chính sách đổi trả &amp; hoàn tiền
          </a>
        </li>
        <li>
          <a href="<?= Url::to('policy/privacy') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-size: 0.9rem; color: <?= $activeType === 'privacy' ? '#2563eb; font-weight: 700; background: #eff6ff;' : '#475569;' ?>">
            <i class="fa-solid fa-user-shield"></i> Chính sách bảo mật thông tin
          </a>
        </li>
      </ul>
    </aside>

    <!-- Policy Content Body -->
    <div class="card-box" style="padding: 36px;">
      <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 24px; padding-bottom: 14px; border-bottom: 2px solid #e2e8f0;">
        <?= htmlspecialchars($policyTitle) ?>
      </h1>

      <div style="line-height: 1.8; color: #334155; font-size: 0.98rem;" class="policy-body">
        <?= $content ?>
      </div>
    </div>
  </div>
</div>
