<?php
namespace App\Controllers;

use App\Models\News;
use App\Models\Product;

class HomeController extends Controller
{
    private Product $productModel;
    private News $newsModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->newsModel = new News();
    }

    public function index(): void
    {
        $featuredProducts = $this->productModel->getFeatured(8);
        $newArrivals      = $this->productModel->getNewArrivals(8);
        $bestSellers      = $this->productModel->getBestSellers(8);
        $onSaleProducts   = $this->productModel->getOnSale(8);
        $latestNews       = $this->newsModel->getLatest(4);

        $this->render('home.index', [
            'pageTitle'        => 'DaNang PC - Siêu Thị Linh Kiện & Phụ Kiện Máy Tính Đà Nẵng',
            'featuredProducts' => $featuredProducts,
            'newArrivals'      => $newArrivals,
            'bestSellers'      => $bestSellers,
            'onSaleProducts'   => $onSaleProducts,
            'latestNews'       => $latestNews,
        ]);
    }
}
