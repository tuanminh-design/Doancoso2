<?php
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div class="container" style="margin: 50px auto 80px; max-width: 480px;">
  <div class="card-box">
    <div style="text-align: center; margin-bottom: 24px;">
      <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
        Đăng Nhập Tài Khoản
      </h1>
      <p style="color: #64748b; font-size: 0.9rem;">
        Đăng nhập để theo dõi đơn hàng và ưu đãi dành riêng cho bạn
      </p>
    </div>

    <form action="<?= Url::to('login') ?>" method="POST">
      <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

      <div class="form-group">
        <label class="form-label" for="email">Địa chỉ Email <span style="color: #dc2626;">*</span></label>
        <input 
          type="email" 
          name="email" 
          id="email" 
          class="form-control" 
          placeholder="name@example.com" 
          required 
          autofocus 
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Mật khẩu <span style="color: #dc2626;">*</span></label>
        <input 
          type="password" 
          name="password" 
          id="password" 
          class="form-control" 
          placeholder="Nhập mật khẩu" 
          required 
        />
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem; margin-top: 10px;">
        <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập
      </button>
    </form>

    <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #64748b;">
      Chưa có tài khoản? 
      <a href="<?= Url::to('register') ?>" style="color: #2563eb; font-weight: 600;">Đăng ký tài khoản mới</a>
    </div>

    <!-- Development Sample Credentials Box (Per Prompt 7) -->
    <div style="margin-top: 24px; padding: 14px; background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; font-size: 0.82rem; color: #475569;">
      <strong style="color: #0f172a; display: block; margin-bottom: 6px;">
        <i class="fa-solid fa-flask"></i> Tài Khoản Mẫu Cho Môi Trường Phát Triển:
      </strong>
      <div>&bull; <strong>Quản trị viên:</strong> <code>admin@danangpc.vn</code> | Pass: <code>Admin@123456</code></div>
      <div>&bull; <strong>Khách hàng:</strong> <code>khachhang@gmail.com</code> | Pass: <code>Customer@123456</code></div>
      <small style="color: #dc2626; display: block; margin-top: 6px;">
        * Lưu ý: Đây là tài khoản dùng cho mục đích kiểm thử (development). Trong môi trường thực tế, hãy đổi mật khẩu ngay lập tức.
      </small>
    </div>
  </div>
</div>
