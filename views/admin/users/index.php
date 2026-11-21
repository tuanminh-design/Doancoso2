<?php
use App\Helpers\Auth;
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;

$csrfToken = Session::csrfToken();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
  <div>
    <h1 style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">Quản Lý Tài Khoản Người Dùng</h1>
    <span style="color: #64748b; font-size: 0.88rem;">Danh sách khách hàng và quản trị viên hệ thống (Tổng: <?= $totalUsers ?>)</span>
  </div>
</div>

<!-- Search -->
<div class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
  <form action="<?= Url::to('admin/users') ?>" method="GET" style="display: flex; gap: 14px; align-items: center;">
    <div style="flex: 1;">
      <input 
        type="text" 
        name="q" 
        class="form-control" 
        placeholder="Tìm theo tên khách hàng, email hoặc số điện thoại..." 
        value="<?= htmlspecialchars($search) ?>" 
      />
    </div>
    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
      <i class="fa-solid fa-magnifying-glass"></i> Tìm
    </button>
    <?php if (!empty($search)): ?>
      <a href="<?= Url::to('admin/users') ?>" class="btn btn-outline" style="padding: 10px 16px;">
        <i class="fa-solid fa-rotate-left"></i> Đặt lại
      </a>
    <?php endif; ?>
  </form>
</div>

<!-- Users Table -->
<div class="admin-card">
  <div class="admin-card-body" style="padding: 0;">
    <?php if (!empty($users)): ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 50px;">ID</th>
            <th>Họ Tên / Email</th>
            <th>Số Điện Thoại</th>
            <th>Địa Chỉ</th>
            <th>Ngày Đăng Ký</th>
            <th style="text-align: center;">Vai Trò</th>
            <th style="text-align: center;">Trạng Thái</th>
            <th style="text-align: center; width: 220px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td>
                <strong style="color: #0f172a;"><?= htmlspecialchars($u['name']) ?></strong>
                <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($u['email']) ?></div>
              </td>
              <td><?= htmlspecialchars($u['phone'] ?: '---') ?></td>
              <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?= htmlspecialchars($u['address'] ?: '---') ?>
              </td>
              <td style="font-size: 0.82rem; color: #64748b;">
                <?= Format::date($u['created_at']) ?>
              </td>
              <td style="text-align: center;">
                <?php if ($u['role'] === 'admin'): ?>
                  <span class="badge badge-primary"><i class="fa-solid fa-shield"></i> Admin</span>
                <?php else: ?>
                  <span class="badge badge-secondary">Khách hàng</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <?php if ($u['status'] === 'active'): ?>
                  <span class="badge badge-success">Hoạt động</span>
                <?php else: ?>
                  <span class="badge badge-danger">Đã khóa</span>
                <?php endif; ?>
              </td>
              <td style="text-align: center;">
                <div style="display: inline-flex; gap: 8px;">
                  <!-- Toggle Status (Active / Blocked) -->
                  <?php if ($u['id'] !== Auth::id()): ?>
                    <form action="<?= Url::to('admin/users/status/' . $u['id']) ?>" method="POST" style="display: inline;">
                      <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                      <?php if ($u['status'] === 'active'): ?>
                        <input type="hidden" name="status" value="blocked">
                        <button type="submit" class="btn btn-sm btn-outline" style="color: #d97706;" title="Khóa tài khoản">
                          <i class="fa-solid fa-lock"></i> Khóa
                        </button>
                      <?php else: ?>
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-sm btn-outline" style="color: #16a34a;" title="Mở khóa tài khoản">
                          <i class="fa-solid fa-lock-open"></i> Mở
                        </button>
                      <?php endif; ?>
                    </form>

                    <!-- Toggle Role (Customer / Admin) -->
                    <form action="<?= Url::to('admin/users/role/' . $u['id']) ?>" method="POST" style="display: inline;">
                      <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                      <?php if ($u['role'] === 'admin'): ?>
                        <input type="hidden" name="role" value="customer">
                        <button type="submit" class="btn btn-sm btn-outline" title="Chuyển thành khách hàng">
                          Hạ quyền
                        </button>
                      <?php else: ?>
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="btn btn-sm btn-primary" title="Cấp quyền Admin">
                          Lên Admin
                        </button>
                      <?php endif; ?>
                    </form>
                  <?php else: ?>
                    <span style="font-size: 0.8rem; color: #16a34a; font-weight: 600;">(Đang đăng nhập)</span>
                  <?php endif; ?>
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
            <a href="<?= Url::to('admin/users', array_filter(['q' => $search, 'page' => $i])) ?>" class="page-item <?= $i === $page ? 'active' : '' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #64748b;">
        Không tìm thấy tài khoản nào phù hợp.
      </div>
    <?php endif; ?>
  </div>
</div>
