<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Middleware\AdminMiddleware;
use App\Models\Category;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    private Product $productModel;
    private Order $orderModel;
    private User $userModel;
    private News $newsModel;

    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->newsModel = new News();
    }

    public function index(): void
    {
        $totalProducts   = $this->productModel->countFilteredProducts(['include_inactive' => true]);
        $totalOrders     = $this->orderModel->countAll();
        $totalRevenue    = $this->orderModel->getTotalRevenue();
        $pendingOrders   = $this->orderModel->getPendingCount();
        $totalCustomers  = $this->userModel->countAll();
        $totalCategories = count($this->categoryModel->getAll());
        $recentOrders    = $this->orderModel->getRecentOrders(8);

        $this->render('admin.dashboard.index', [
            'pageTitle'       => 'Bảng Điều Khiển Quản Trị - DaNang PC',
            'totalProducts'   => $totalProducts,
            'totalOrders'     => $totalOrders,
            'totalRevenue'    => $totalRevenue,
            'pendingOrders'   => $pendingOrders,
            'totalCustomers'  => $totalCustomers,
            'totalCategories' => $totalCategories,
            'recentOrders'    => $recentOrders,
        ], 'admin');
    }
}
