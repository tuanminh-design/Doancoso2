<?php
use App\Helpers\Url;
?>
<div class="container" style="margin: 80px auto; text-align: center; max-width: 560px;">
  <div class="card-box" style="padding: 60px 30px;">
    <div style="font-size: 5rem; font-weight: 900; color: #2563eb; line-height: 1; margin-bottom: 16px;">
      404
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 12px;">
      Không Tìm Thấy Trang
    </h2>
    <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 28px; line-height: 1.5;">
      Trang bạn đang tìm kiếm có thể đã bị xóa, thay đổi đường dẫn hoặc tạm thời không khả dụng.
    </p>
    <div style="display: flex; justify-content: center; gap: 14px;">
      <a href="<?= Url::to('/') ?>" class="btn btn-primary">
        <i class="fa-solid fa-house"></i> Về Trang Chủ
      </a>
      <a href="<?= Url::to('products') ?>" class="btn btn-outline">
        <i class="fa-solid fa-boxes-stacked"></i> Xem Sản Phẩm
      </a>
    </div>
  </div>
</div>
