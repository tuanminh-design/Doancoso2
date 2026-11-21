<?php
namespace App\Middleware;

use App\Helpers\Auth;
use App\Helpers\Session;
use App\Helpers\Url;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Vui lòng đăng nhập để tiếp tục.');
            Session::set('return_url', $_SERVER['REQUEST_URI'] ?? '/');
            Url::redirect('login');
        }
    }
}
