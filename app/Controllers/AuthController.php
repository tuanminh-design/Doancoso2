<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Models\User;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                Url::redirect('admin');
            } else {
                Url::redirect('account');
            }
            return;
        }

        $this->render('account.login', [
            'pageTitle' => 'Đăng Nhập Tài Khoản - DaNang PC',
        ]);
    }

    public function login(): void
    {
        $this->validateCsrf();

        $email    = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $validator = Validator::make($_POST);
        $validator->required('email', 'Vui lòng nhập email.')
                  ->email('email', 'Email không hợp lệ.')
                  ->required('password', 'Vui lòng nhập mật khẩu.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('login');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::setFlash('error', 'Email hoặc mật khẩu không chính xác.');
            Url::redirect('login');
            return;
        }

        if ($user['status'] === 'blocked') {
            Session::setFlash('error', 'Tài khoản của bạn đã bị tạm khóa. Vui lòng liên hệ quản trị viên.');
            Url::redirect('login');
            return;
        }

        // Login user safely and regenerate session
        Auth::login($user);

        Session::setFlash('success', 'Đăng nhập thành công! Chào mừng bạn, ' . htmlspecialchars($user['name']));

        // Check if there is a return URL
        $returnUrl = Session::get('return_url');
        Session::remove('return_url');

        if ($returnUrl) {
            header('Location: ' . $returnUrl);
            exit;
        }

        if ($user['role'] === 'admin') {
            Url::redirect('admin');
        } else {
            Url::redirect('account');
        }
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            Url::redirect('account');
            return;
        }

        $this->render('account.register', [
            'pageTitle' => 'Đăng Ký Tài Khoản Mới - DaNang PC',
        ]);
    }

    public function register(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('name', 'Họ tên là bắt buộc.')
                  ->maxLength('name', 100)
                  ->required('email', 'Email là bắt buộc.')
                  ->email('email', 'Email không hợp lệ.')
                  ->required('password', 'Mật khẩu là bắt buộc.')
                  ->minLength('password', 6, 'Mật khẩu phải từ 6 ký tự trở lên.')
                  ->matches('password_confirm', 'password', 'Mật khẩu xác nhận không khớp.')
                  ->phone('phone', 'Số điện thoại không hợp lệ.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('register');
            return;
        }

        $email = trim($_POST['email']);
        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            Session::setFlash('error', 'Email này đã được đăng ký. Vui lòng sử dụng email khác hoặc đăng nhập.');
            Url::redirect('register');
            return;
        }

        $userId = $this->userModel->create([
            'name'     => trim($_POST['name']),
            'email'    => $email,
            'phone'    => !empty($_POST['phone']) ? trim($_POST['phone']) : null,
            'password' => (string)$_POST['password'],
            'address'  => !empty($_POST['address']) ? trim($_POST['address']) : null,
            'role'     => 'customer',
            'status'   => 'active',
        ]);

        $newUser = $this->userModel->findById($userId);
        Auth::login($newUser);

        Session::setFlash('success', 'Đăng ký tài khoản thành công! Chào mừng bạn đến với DaNang PC.');
        Url::redirect('account');
    }

    public function logout(): void
    {
        Auth::logout();
        Session::setFlash('success', 'Bạn đã đăng xuất an toàn.');
        Url::redirect('login');
    }
}
