<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
$siteName     = $settings['site_name'] ?? 'DaNang PC';
$storePhone   = $settings['store_phone'] ?? '0905.123.456 - 0236.3888.999';
$storeEmail   = $settings['store_email'] ?? 'contact@danangpc.vn';
$storeAddress = $settings['store_address'] ?? '123 Nguyễn Văn Linh, P. Nam Dương, Q. Hải Châu, TP. Đà Nẵng';
$workingHours = $settings['working_hours'] ?? 'Thứ 2 - Chủ Nhật: 08:00 - 21:00';
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Liên Hệ &amp; Hỗ Trợ Khách Hàng</span>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; align-items: start;">
    <!-- Store Info Card -->
    <div class="card-box" style="padding: 32px;">
      <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 14px;">
        Thông Tin Liên Hệ DaNang PC
      </h1>
      <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 24px;">
        Quý khách cần tư vấn lắp ráp PC Gaming, thiết kế đồ họa, nâng cấp linh kiện hoặc bảo hành sản phẩm, vui lòng liên hệ với showroom chúng tôi qua các kênh dưới đây:
      </p>

      <div style="display: flex; flex-direction: column; gap: 18px; margin-bottom: 28px;">
        <div style="display: flex; gap: 14px; align-items: flex-start;">
          <div style="width: 42px; height: 42px; background: #eff6ff; color: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div>
            <strong style="color: #0f172a; display: block; font-size: 0.92rem;">Địa chỉ showroom tại Đà Nẵng:</strong>
            <span style="color: #475569; font-size: 0.9rem;"><?= htmlspecialchars($storeAddress) ?></span>
          </div>
        </div>

        <div style="display: flex; gap: 14px; align-items: flex-start;">
          <div style="width: 42px; height: 42px; background: #ecfdf5; color: #059669; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
            <i class="fa-solid fa-phone"></i>
          </div>
          <div>
            <strong style="color: #0f172a; display: block; font-size: 0.92rem;">Hotline tư vấn &amp; bán hàng:</strong>
            <span style="color: #475569; font-size: 0.9rem; font-weight: 700;"><?= htmlspecialchars($storePhone) ?></span>
          </div>
        </div>

        <div style="display: flex; gap: 14px; align-items: flex-start;">
          <div style="width: 42px; height: 42px; background: #fffbeb; color: #d97706; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
            <i class="fa-solid fa-envelope"></i>
          </div>
          <div>
            <strong style="color: #0f172a; display: block; font-size: 0.92rem;">Email chăm sóc khách hàng:</strong>
            <span style="color: #475569; font-size: 0.9rem;"><?= htmlspecialchars($storeEmail) ?></span>
          </div>
        </div>

        <div style="display: flex; gap: 14px; align-items: flex-start;">
          <div style="width: 42px; height: 42px; background: #f1f5f9; color: #475569; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
            <i class="fa-solid fa-clock"></i>
          </div>
          <div>
            <strong style="color: #0f172a; display: block; font-size: 0.92rem;">Thời gian phục vụ:</strong>
            <span style="color: #475569; font-size: 0.9rem;"><?= htmlspecialchars($workingHours) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="card-box" style="padding: 32px;">
      <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
        Gửi Tin Nhắn Cho Chúng Tôi
      </h2>
      <p style="color: #64748b; font-size: 0.88rem; margin-bottom: 22px;">
        Điền thông tin và yêu cầu của bạn, đội ngũ kỹ thuật viên DaNang PC sẽ liên hệ lại trong thời gian sớm nhất.
      </p>

      <form action="<?= Url::to('contact/submit') ?>" method="POST">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label class="form-label" for="name">Họ và tên của bạn <span style="color: #dc2626;">*</span></label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control" 
              placeholder="Nguyễn Văn A" 
              required 
            />
          </div>

          <div class="form-group">
            <label class="form-label" for="phone">Số điện thoại</label>
            <input 
              type="tel" 
              name="phone" 
              id="phone" 
              class="form-control" 
              placeholder="0905xxxxxx" 
            />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Địa chỉ Email <span style="color: #dc2626;">*</span></label>
          <input 
            type="email" 
            name="email" 
            id="email" 
            class="form-control" 
            placeholder="email@example.com" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="subject">Tiêu đề yêu cầu <span style="color: #dc2626;">*</span></label>
          <input 
            type="text" 
            name="subject" 
            id="subject" 
            class="form-control" 
            placeholder="Tư vấn cấu hình máy, báo giá linh kiện..." 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="message">Nội dung chi tiết <span style="color: #dc2626;">*</span></label>
          <textarea 
            name="message" 
            id="message" 
            class="form-control" 
            rows="4" 
            placeholder="Mô tả nhu cầu sử dụng, ngân sách dự kiến..." 
            required
          ></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700;">
          <i class="fa-solid fa-paper-plane"></i> Gửi Tin Nhắn
        </button>
      </form>
    </div>
  </div>
</div>
