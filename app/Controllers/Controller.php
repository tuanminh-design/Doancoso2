<?php
namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\Category;
use App\Models\Setting;
use App\Services\CartService;

abstract class Controller
{
    protected Setting $settingModel;
    protected Category $categoryModel;
    protected CartService $cartService;

    public function __construct()
    {
        $this->settingModel = new Setting();
        $this->categoryModel = new Category();
        $this->cartService = new CartService();
    }

    /**
     * Render view template with shared layout data
     */
    protected function render(string $viewPath, array $data = [], string $layout = 'default'): void
    {
        // Extract variables for view template
        extract($data);

        // Global shared variables
        $siteSettings = $this->settingModel->getAll();
        $navCategories = $this->categoryModel->getAllActive();
        $cartCount = $this->cartService->count();
        $currentUser = Auth::user();
        $isAdmin = Auth::isAdmin();

        $flashSuccess = Session::getFlash('success');
        $flashError = Session::getFlash('error');
        $flashInfo = Session::getFlash('info');

        $viewFile = dirname(__DIR__, 2) . '/views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            die("View [{$viewPath}] không tồn tại tại: {$viewFile}");
        }

        // Buffer view content
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Render with appropriate layout
        if ($layout === 'admin') {
            include dirname(__DIR__, 2) . '/views/layouts/admin_layout.php';
        } elseif ($layout === 'none') {
            echo $content;
        } else {
            include dirname(__DIR__, 2) . '/views/layouts/main_layout.php';
        }
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function validateCsrf(): void
    {
        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!Session::verifyCsrfToken($token)) {
            Session::setFlash('error', 'Phiên làm việc đã hết hạn hoặc mã xác thực không hợp lệ. Vui lòng tải lại trang.');
            Url::back();
        }
    }
}
