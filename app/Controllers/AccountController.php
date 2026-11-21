<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Middleware\AuthMiddleware;
use App\Models\Order;
use App\Models\User;

class AccountController extends Controller
{
    private User $userModel;
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        AuthMiddleware::handle();
        $this->userModel = new User();
        $this->orderModel = new Order();
    }

    public function profile(): void
    {
        $user = $this->userModel->findById(Auth::id());
        $recentOrders = $this->orderModel->getByUser(Auth::id(), 5);

        $this->render('account.profile', [
            'pageTitle'    => 'Quản Lý Tài Khoản - DaNang PC',
            'user'         => $user,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function updateProfile(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('name', 'Họ tên không được để trống.')
                  ->maxLength('name', 100)
                  ->phone('phone', 'Số điện thoại không hợp lệ.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('account');
            return;
        }

        $userId = Auth::id();
        $this->userModel->updateProfile($userId, [
            'name'    => trim($_POST['name']),
            'phone'   => !empty($_POST['phone']) ? trim($_POST['phone']) : null,
            'address' => !empty($_POST['address']) ? trim($_POST['address']) : null,
        ]);

        $fresh = $this->userModel->findById($userId);
        Auth::updateSessionUser($fresh);

        Session::setFlash('success', 'Cập nhật thông tin tài khoản thành công!');
        Url::redirect('account');
    }

    public function changePassword(): void
    {
        $this->validateCsrf();

        $currentPass = (string)($_POST['current_password'] ?? '');
        $newPass     = (string)($_POST['new_password'] ?? '');
        $confirmPass = (string)($_POST['new_password_confirm'] ?? '');

        $validator = Validator::make($_POST);
        $validator->required('current_password', 'Vui lòng nhập mật khẩu hiện tại.')
                  ->required('new_password', 'Vui lòng nhập mật khẩu mới.')
                  ->minLength('new_password', 6, 'Mật khẩu mới phải từ 6 ký tự trở lên.')
                  ->matches('new_password_confirm', 'new_password', 'Xác nhận mật khẩu mới không khớp.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('account');
            return;
        }

        $user = $this->userModel->findById(Auth::id());
        if (!password_verify($currentPass, $user['password'])) {
            Session::setFlash('error', 'Mật khẩu hiện tại không chính xác.');
            Url::redirect('account');
            return;
        }

        $this->userModel->updatePassword(Auth::id(), $newPass);
        Session::setFlash('success', 'Đổi mật khẩu thành công!');
        Url::redirect('account');
    }

    public function orders(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getByUser(Auth::id(), $limit, $offset);
        $totalOrders = $this->orderModel->countByUser(Auth::id());
        $totalPages = max(1, (int)ceil($totalOrders / $limit));

        $this->render('account.orders', [
            'pageTitle'   => 'Lịch Sử Đơn Hàng - DaNang PC',
            'orders'      => $orders,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'totalOrders' => $totalOrders,
        ]);
    }

    public function orderDetail(string $code): void
    {
        $order = $this->orderModel->findByCode($code);

        // Security check: Customer can only view their own orders (unless admin)
        if (!$order || ($order['user_id'] != Auth::id() && !Auth::isAdmin())) {
            http_response_code(404);
            $this->render('pages.404', ['pageTitle' => 'Đơn hàng không tồn tại']);
            return;
        }

        $items = $this->orderModel->getItems($order['id']);
        $history = $this->orderModel->getHistory($order['id']);

        $this->render('account.order_detail', [
            'pageTitle' => 'Chi Tiết Đơn Hàng ' . $order['order_code'] . ' - DaNang PC',
            'order'     => $order,
            'items'     => $items,
            'history'   => $history,
        ]);
    }
}
