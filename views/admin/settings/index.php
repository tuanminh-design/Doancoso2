<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Cấu Hình Website &amp; Chính Sách</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Thiết lập thông tin cửa hàng, ngân hàng thanh toán và các chính sách dịch vụ</span>
  </div>
</div>

<form action="<?= Url::to('admin/settings/update') ?>" method="POST">
  <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; margin-bottom: 24px;">
    <!-- 1. Store Basic Information -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">
          <i class="fa-solid fa-store" style="color: #2563eb;"></i> Thông Tin Cửa Hàng
        </h3>
      </div>
      <div class="admin-card-body">
        <div class="form-group">
          <label class="form-label" for="site_name">Tên cửa hàng / Tiêu đề website</label>
          <input 
            type="text" 
            name="site_name" 
            id="site_name" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['site_name'] ?? 'DaNang PC') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="site_tagline">Khẩu hiệu / Slogan</label>
          <input 
            type="text" 
            name="site_tagline" 
            id="site_tagline" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>" 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="store_phone">Số điện thoại Hotline</label>
          <input 
            type="text" 
            name="store_phone" 
            id="store_phone" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="store_email">Email liên hệ</label>
          <input 
            type="email" 
            name="store_email" 
            id="store_email" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="store_address">Địa chỉ showroom tại Đà Nẵng</label>
          <input 
            type="text" 
            name="store_address" 
            id="store_address" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="working_hours">Giờ mở cửa phục vụ</label>
          <input 
            type="text" 
            name="working_hours" 
            id="working_hours" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['working_hours'] ?? '') ?>" 
          />
        </div>
      </div>
    </div>

    <!-- 2. Bank Transfer Payment Information -->
    <div class="admin-card">
      <div class="admin-card-header">
        <h3 class="admin-card-title">
          <i class="fa-solid fa-building-columns" style="color: #2563eb;"></i> Thông Tin Thanh Toán Ngân Hàng
        </h3>
      </div>
      <div class="admin-card-body">
        <div class="form-group">
          <label class="form-label" for="bank_name">Tên Ngân Hàng</label>
          <input 
            type="text" 
            name="bank_name" 
            id="bank_name" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['bank_name'] ?? 'Vietcombank') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="bank_account_number">Số Tài Khoản</label>
          <input 
            type="text" 
            name="bank_account_number" 
            id="bank_account_number" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['bank_account_number'] ?? '') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="bank_account_name">Tên Chủ Tài Khoản (In hoa không dấu)</label>
          <input 
            type="text" 
            name="bank_account_name" 
            id="bank_account_name" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['bank_account_name'] ?? '') ?>" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="bank_branch">Chi Nhánh Ngân Hàng</label>
          <input 
            type="text" 
            name="bank_branch" 
            id="bank_branch" 
            class="form-control" 
            value="<?= htmlspecialchars($settings['bank_branch'] ?? '') ?>" 
          />
        </div>

        <div style="background-color: #f8fafc; border-radius: 6px; padding: 14px; border: 1px dashed #cbd5e1; font-size: 0.82rem; color: #64748b; line-height: 1.5;">
          <strong style="color: #0f172a; display: block; margin-bottom: 4px;">Lưu ý về đơn chuyển khoản:</strong>
          Thông tin ngân hàng này sẽ hiển thị trực tiếp cho khách hàng trên trang thanh toán và trang xác nhận đơn hàng thành công kèm mã đơn hàng để đối soát.
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Policy Content Editors -->
  <div class="admin-card" style="margin-bottom: 24px;">
    <div class="admin-card-header">
      <h3 class="admin-card-title">
        <i class="fa-solid fa-file-contract" style="color: #2563eb;"></i> Nội Dung Các Chính Sách Dịch Vụ
      </h3>
    </div>
    <div class="admin-card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group">
          <label class="form-label" for="policy_warranty">Chính Sách Bảo Hành Chính Hãng (Hỗ trợ HTML)</label>
          <textarea 
            name="policy_warranty" 
            id="policy_warranty" 
            class="form-control" 
            rows="6"
          ><?= htmlspecialchars($settings['policy_warranty'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="policy_shipping">Chính Sách Vận Chuyển &amp; Giao Hàng</label>
          <textarea 
            name="policy_shipping" 
            id="policy_shipping" 
            class="form-control" 
            rows="6"
          ><?= htmlspecialchars($settings['policy_shipping'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="policy_return">Chính Sách Đổi Trả &amp; Hoàn Tiền</label>
          <textarea 
            name="policy_return" 
            id="policy_return" 
            class="form-control" 
            rows="6"
          ><?= htmlspecialchars($settings['policy_return'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="policy_privacy">Chính Sách Bảo Mật Thông Tin</label>
          <textarea 
            name="policy_privacy" 
            id="policy_privacy" 
            class="form-control" 
            rows="6"
          ><?= htmlspecialchars($settings['policy_privacy'] ?? '') ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <div style="text-align: right;">
    <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 1.05rem; font-weight: 700;">
      <i class="fa-solid fa-floppy-disk"></i> Lưu Tất Cả Cấu Hình
    </button>
  </div>
</form>
