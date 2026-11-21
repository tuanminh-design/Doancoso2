<?php
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div class="container" style="margin: 30px auto 60px;">
  <!-- Breadcrumb -->
  <div style="margin-bottom: 20px; font-size: 0.85rem; color: #64748b;">
    <a href="<?= Url::to('/') ?>"><i class="fa-solid fa-house"></i> Trang Chủ</a>
    <span style="margin: 0 6px;">/</span>
    <span style="color: #0f172a; font-weight: 600;">Thông Tin Tài Khoản</span>
  </div>

  <div style="display: grid; grid-template-columns: 260px 1fr; gap: 30px;">
    <!-- Account Sidebar -->
    <div>
      <div class="card-box" style="padding: 20px; text-align: center;">
        <div style="width: 64px; height: 64px; background: #2563eb; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 12px;">
          <i class="fa-regular fa-user"></i>
        </div>
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
          <?= htmlspecialchars($user['name']) ?>
        </h3>
        <span style="font-size: 0.82rem; color: #64748b;"><?= htmlspecialchars($user['email']) ?></span>
      </div>

      <div class="card-box" style="padding: 10px 0;">
        <ul style="list-style: none;">
          <li>
            <a href="<?= Url::to('account') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-weight: 600; color: #2563eb; background: #eff6ff;">
              <i class="fa-solid fa-user-pen"></i> Thông tin cá nhân
            </a>
          </li>
          <li>
            <a href="<?= Url::to('account/orders') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #475569;">
              <i class="fa-solid fa-clipboard-list"></i> Lịch sử đơn hàng
            </a>
          </li>
          <li>
            <a href="<?= Url::to('logout') ?>" style="display: flex; align-items: center; gap: 10px; padding: 12px 20px; color: #dc2626;">
              <i class="fa-solid fa-power-off"></i> Đăng xuất
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Forms Area -->
    <div>
      <!-- 1. Profile Information -->
      <div class="card-box">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-id-card" style="color: #2563eb;"></i> Cập Nhật Thông Tin Cá Nhân
        </h3>

        <form action="<?= Url::to('account/update') ?>" method="POST">
          <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

          <div class="form-group">
            <label class="form-label" for="name">Họ và tên <span style="color: #dc2626;">*</span></label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control" 
              value="<?= htmlspecialchars($user['name']) ?>" 
              required 
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="email">Địa chỉ Email</label>
              <input 
                type="email" 
                id="email" 
                class="form-control" 
                value="<?= htmlspecialchars($user['email']) ?>" 
                disabled 
                style="background-color: #f1f5f9; color: #64748b;"
              />
              <small style="color: #94a3b8;">Email không thể thay đổi để đảm bảo an toàn tài khoản.</small>
            </div>

            <div class="form-group">
              <label class="form-label" for="phone">Số điện thoại</label>
              <input 
                type="tel" 
                name="phone" 
                id="phone" 
                class="form-control" 
                value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                placeholder="Nhập số điện thoại"
              />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="address">Địa chỉ giao hàng mặc định</label>
            <textarea 
              name="address" 
              id="address" 
              class="form-control" 
              rows="2" 
              placeholder="Nhập địa chỉ giao hàng tại Đà Nẵng..."
            ><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
          </button>
        </form>
      </div>

      <!-- 2. Change Password Form -->
      <div class="card-box">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-key" style="color: #2563eb;"></i> Đổi Mật Khẩu
        </h3>

        <form action="<?= Url::to('account/password') ?>" method="POST">
          <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

          <div class="form-group">
            <label class="form-label" for="current_password">Mật khẩu hiện tại <span style="color: #dc2626;">*</span></label>
            <input 
              type="password" 
              name="current_password" 
              id="current_password" 
              class="form-control" 
              placeholder="Nhập mật khẩu hiện tại" 
              required 
            />
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
              <label class="form-label" for="new_password">Mật khẩu mới <span style="color: #dc2626;">*</span></label>
              <input 
                type="password" 
                name="new_password" 
                id="new_password" 
                class="form-control" 
                placeholder="Tối thiểu 6 ký tự" 
                required 
              />
            </div>

            <div class="form-group">
              <label class="form-label" for="new_password_confirm">Xác nhận mật khẩu mới <span style="color: #dc2626;">*</span></label>
              <input 
                type="password" 
                name="new_password_confirm" 
                id="new_password_confirm" 
                class="form-control" 
                placeholder="Nhập lại mật khẩu mới" 
                required 
              />
            </div>
          </div>

          <button type="submit" class="btn btn-outline" style="font-weight: 600;">
            <i class="fa-solid fa-shield-check"></i> Cập Nhật Mật Khẩu
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
