<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Middleware\AdminMiddleware;
use App\Models\User;

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
        $this->userModel = new User();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $search = !empty($_GET['q']) ? trim($_GET['q']) : '';

        $users = $this->userModel->getAll($search, $limit, $offset);
        $totalUsers = $this->userModel->countAll($search);
        $totalPages = max(1, (int)ceil($totalUsers / $limit));

        $this->render('admin.users.index', [
            'pageTitle'  => 'Quản Lý Tài Khoản Khách Hàng - DaNang PC Admin',
            'users'      => $users,
            'totalUsers' => $totalUsers,
            'page'       => $page,
            'totalPages' => $totalPages,
            'search'     => $search,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->validateCsrf();
        $userId = (int)$id;

        // Prevent self-lockout
        if ($userId === Auth::id()) {
            Session::setFlash('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
            Url::redirect('admin/users');
            return;
        }

        $status = trim($_POST['status'] ?? '');
        if (!in_array($status, ['active', 'blocked'])) {
            Session::setFlash('error', 'Trạng thái tài khoản không hợp lệ.');
            Url::redirect('admin/users');
            return;
        }

        $this->userModel->updateStatus($userId, $status);
        Session::setFlash('success', 'Đã cập nhật trạng thái tài khoản thành công.');
        Url::redirect('admin/users');
    }

    public function updateRole(string $id): void
    {
        $this->validateCsrf();
        $userId = (int)$id;

        // Prevent self-demotion
        if ($userId === Auth::id()) {
            Session::setFlash('error', 'Bạn không thể tự thay đổi vai trò tài khoản của chính mình.');
            Url::redirect('admin/users');
            return;
        }

        $role = trim($_POST['role'] ?? '');
        if (!in_array($role, ['customer', 'admin'])) {
            Session::setFlash('error', 'Vai trò không hợp lệ.');
            Url::redirect('admin/users');
            return;
        }

        $this->userModel->updateRole($userId, $role);
        Session::setFlash('success', 'Đã cập nhật quyền tài khoản thành công.');
        Url::redirect('admin/users');
    }
}
