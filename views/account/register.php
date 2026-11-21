<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div class="container" style="margin: 50px auto 80px; max-width: 500px;">
  <div class="card-box">
    <div style="text-align: center; margin-bottom: 24px;">
      <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
        Đăng Ký Tài Khoản Mới
      </h1>
      <p style="color: #64748b; font-size: 0.9rem;">
        Trở thành thành viên DaNang PC để nhận nhiều quyền lợi và ưu đãi
      </p>
    </div>

    <form action="<?= Url::to('register') ?>" method="POST">
      <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

      <div class="form-group">
        <label class="form-label" for="name">Họ và tên <span style="color: #dc2626;">*</span></label>
        <input 
          type="text" 
          name="name" 
          id="name" 
          class="form-control" 
          placeholder="Ví dụ: Nguyễn Văn An" 
          required 
          autofocus 
        />
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
        <label class="form-label" for="phone">Số điện thoại</label>
        <input 
          type="tel" 
          name="phone" 
          id="phone" 
          class="form-control" 
          placeholder="Ví dụ: 0905123456" 
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="address">Địa chỉ mặc định (tùy chọn)</label>
        <input 
          type="text" 
          name="address" 
          id="address" 
          class="form-control" 
          placeholder="Địa chỉ tại Đà Nẵng..." 
        />
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="form-group">
          <label class="form-label" for="password">Mật khẩu <span style="color: #dc2626;">*</span></label>
          <input 
            type="password" 
            name="password" 
            id="password" 
            class="form-control" 
            placeholder="Tối thiểu 6 ký tự" 
            required 
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirm">Xác nhận mật khẩu <span style="color: #dc2626;">*</span></label>
          <input 
            type="password" 
            name="password_confirm" 
            id="password_confirm" 
            class="form-control" 
            placeholder="Nhập lại mật khẩu" 
            required 
          />
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem; margin-top: 10px;">
        <i class="fa-solid fa-user-plus"></i> Hoàn Tất Đăng Ký
      </button>
    </form>

    <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #64748b;">
      Đã có tài khoản? 
      <a href="<?= Url::to('login') ?>" style="color: #2563eb; font-weight: 600;">Đăng nhập ngay</a>
    </div>
  </div>
</div>
