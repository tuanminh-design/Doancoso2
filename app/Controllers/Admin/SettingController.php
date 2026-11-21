<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Middleware\AdminMiddleware;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
    }

    public function index(): void
    {
        $settings = $this->settingModel->getAll();

        $this->render('admin.settings.index', [
            'pageTitle' => 'Cài Đặt Hệ Thống & Chính Sách - DaNang PC Admin',
            'settings'  => $settings,
        ], 'admin');
    }

    public function update(): void
    {
        $this->validateCsrf();

        $allowedKeys = [
            'site_name',
            'site_tagline',
            'store_phone',
            'store_email',
            'store_address',
            'working_hours',
            'bank_name',
            'bank_branch',
            'bank_account_number',
            'bank_account_name',
            'policy_shipping',
            'policy_warranty',
            'policy_return',
            'policy_privacy',
        ];

        $payload = [];
        foreach ($allowedKeys as $k) {
            if (isset($_POST[$k])) {
                $payload[$k] = trim($_POST[$k]);
            }
        }

        $this->settingModel->updateMultiple($payload);

        Session::setFlash('success', 'Đã lưu cấu hình website và nội dung chính sách thành công!');
        Url::redirect('admin/settings');
    }
}
