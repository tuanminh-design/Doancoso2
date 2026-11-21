<?php
namespace App\Controllers;

use App\Helpers\Url;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    private Product $productModel;
    private Brand $brandModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->brandModel = new Brand();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $filters = [
            'category_slug' => !empty($_GET['category']) ? trim($_GET['category']) : null,
            'brand_slug'    => !empty($_GET['brand']) ? trim($_GET['brand']) : null,
            'min_price'     => !empty($_GET['min_price']) ? (float)$_GET['min_price'] : null,
            'max_price'     => !empty($_GET['max_price']) ? (float)$_GET['max_price'] : null,
            'in_stock'      => isset($_GET['in_stock']) && $_GET['in_stock'] !== '' ? (int)$_GET['in_stock'] : null,
            'search'        => !empty($_GET['q']) ? trim($_GET['q']) : null,
            'sort'          => !empty($_GET['sort']) ? trim($_GET['sort']) : 'newest',
        ];

        $products = $this->productModel->getFilteredProducts($filters, $limit, $offset);
        $totalProducts = $this->productModel->countFilteredProducts($filters);
        $totalPages = max(1, (int)ceil($totalProducts / $limit));

        // Get filter sidebar data
        $categories = $this->categoryModel->getAllActive();
        $brands = $this->brandModel->getAll();

        // Active category / brand object for breadcrumb
        $activeCategory = null;
        if (!empty($filters['category_slug'])) {
            $activeCategory = $this->categoryModel->findBySlug($filters['category_slug']);
        }

        $activeBrand = null;
        if (!empty($filters['brand_slug'])) {
            $activeBrand = $this->brandModel->findBySlug($filters['brand_slug']);
        }

        $pageTitle = 'Sản Phẩm Linh Kiện & Phụ Kiện PC - DaNang PC';
        if ($activeCategory) {
            $pageTitle = $activeCategory['name'] . ' - DaNang PC';
        } elseif (!empty($filters['search'])) {
            $pageTitle = 'Tìm kiếm: ' . htmlspecialchars($filters['search']) . ' - DaNang PC';
        }

        $this->render('products.index', [
            'pageTitle'      => $pageTitle,
            'products'       => $products,
            'totalProducts'  => $totalProducts,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'filters'        => $filters,
            'categories'     => $categories,
            'brands'         => $brands,
            'activeCategory' => $activeCategory,
            'activeBrand'    => $activeBrand,
        ]);
    }

    public function detail(string $slug): void
    {
        $product = $this->productModel->findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            $this->render('pages.404', ['pageTitle' => 'Sản phẩm không tồn tại - DaNang PC']);
            return;
        }

        // Increment view count
        $this->productModel->incrementView($product['id']);

        $specs = $this->productModel->getSpecs($product['id']);
        $relatedProducts = $this->productModel->getRelated($product['id'], $product['category_id'], 4);

        $this->render('products.detail', [
            'pageTitle'       => $product['name'] . ' - DaNang PC',
            'product'         => $product,
            'specs'           => $specs,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
