<?php
namespace App\Middleware;

use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;

class AdminMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Vui lòng đăng nhập với tài khoản Quản Trị Viên.');
            Session::set('return_url', $_SERVER['REQUEST_URI'] ?? '/admin');
            Url::redirect('login');
        }

        if (!Auth::isAdmin()) {
            Session::setFlash('error', 'Bạn không có quyền truy cập vào trang Quản trị hệ thống.');
            Url::redirect('');
        }
    }
}
