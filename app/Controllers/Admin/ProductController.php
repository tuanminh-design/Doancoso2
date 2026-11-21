<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Middleware\AdminMiddleware;
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
        AdminMiddleware::handle();
        $this->productModel = new Product();
        $this->brandModel = new Brand();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $filters = [
            'category_id'      => !empty($_GET['category_id']) ? (int)$_GET['category_id'] : null,
            'search'           => !empty($_GET['q']) ? trim($_GET['q']) : null,
            'include_inactive' => true,
        ];

        $products = $this->productModel->getFilteredProducts($filters, $limit, $offset);
        $totalProducts = $this->productModel->countFilteredProducts($filters);
        $totalPages = max(1, (int)ceil($totalProducts / $limit));

        $categories = $this->categoryModel->getAll();

        $this->render('admin.products.index', [
            'pageTitle'     => 'Quản Lý Sản Phẩm - DaNang PC Admin',
            'products'      => $products,
            'totalProducts' => $totalProducts,
            'page'          => $page,
            'totalPages'    => $totalPages,
            'filters'       => $filters,
            'categories'    => $categories,
        ], 'admin');
    }

    public function create(): void
    {
        $categories = $this->categoryModel->getAllActive();
        $brands = $this->brandModel->getAll();

        $this->render('admin.products.create', [
            'pageTitle'  => 'Thêm Sản Phẩm Mới - DaNang PC Admin',
            'categories' => $categories,
            'brands'     => $brands,
        ], 'admin');
    }

    public function store(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('name', 'Tên sản phẩm không được để trống.')
                  ->required('sku', 'Mã SKU không được để trống.')
                  ->required('category_id', 'Vui lòng chọn danh mục.')
                  ->required('price', 'Vui lòng nhập giá bán.')
                  ->numeric('price', 'Giá bán phải là số.')
                  ->minVal('price', 0, 'Giá bán không thể âm.')
                  ->numeric('stock_quantity', 'Tồn kho phải là số.')
                  ->minVal('stock_quantity', 0, 'Tồn kho không thể âm.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/products/create');
            return;
        }

        // Handle safe image upload
        $thumbnail = $this->handleImageUpload('thumbnail');

        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($_POST['name']);

        $productId = $this->productModel->create([
            'category_id'       => (int)$_POST['category_id'],
            'brand_id'          => !empty($_POST['brand_id']) ? (int)$_POST['brand_id'] : null,
            'name'              => trim($_POST['name']),
            'sku'               => strtoupper(trim($_POST['sku'])),
            'slug'              => $slug,
            'price'             => (float)$_POST['price'],
            'sale_price'        => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            'stock_quantity'    => (int)($_POST['stock_quantity'] ?? 0),
            'thumbnail'         => $thumbnail,
            'short_description' => !empty($_POST['short_description']) ? trim($_POST['short_description']) : null,
            'description'       => !empty($_POST['description']) ? trim($_POST['description']) : null,
            'is_featured'       => isset($_POST['is_featured']),
            'is_active'         => isset($_POST['is_active']),
        ]);

        // Handle Specs
        if (!empty($_POST['spec_names']) && is_array($_POST['spec_names'])) {
            $specs = [];
            foreach ($_POST['spec_names'] as $i => $name) {
                $val = $_POST['spec_values'][$i] ?? '';
                if (trim($name) !== '' && trim($val) !== '') {
                    $specs[] = ['name' => $name, 'value' => $val];
                }
            }
            $this->productModel->saveSpecs($productId, $specs);
        }

        Session::setFlash('success', 'Thêm sản phẩm mới thành công!');
        Url::redirect('admin/products');
    }

    public function edit(string $id): void
    {
        $product = $this->productModel->findById((int)$id);
        if (!$product) {
            Session::setFlash('error', 'Sản phẩm không tồn tại.');
            Url::redirect('admin/products');
            return;
        }

        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();
        $specs = $this->productModel->getSpecs($product['id']);

        $this->render('admin.products.edit', [
            'pageTitle'  => 'Chỉnh Sửa Sản Phẩm: ' . $product['name'],
            'product'    => $product,
            'categories' => $categories,
            'brands'     => $brands,
            'specs'      => $specs,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $productId = (int)$id;

        $product = $this->productModel->findById($productId);
        if (!$product) {
            Session::setFlash('error', 'Sản phẩm không tồn tại.');
            Url::redirect('admin/products');
            return;
        }

        $validator = Validator::make($_POST);
        $validator->required('name', 'Tên sản phẩm không được để trống.')
                  ->required('sku', 'Mã SKU không được để trống.')
                  ->required('category_id', 'Vui lòng chọn danh mục.')
                  ->required('price', 'Vui lòng nhập giá bán.')
                  ->numeric('price', 'Giá bán phải là số.')
                  ->minVal('price', 0, 'Giá bán không thể âm.')
                  ->numeric('stock_quantity', 'Tồn kho phải là số.')
                  ->minVal('stock_quantity', 0, 'Tồn kho không thể âm.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/products/edit/' . $productId);
            return;
        }

        // Handle image upload if provided, otherwise keep existing
        $newThumbnail = $this->handleImageUpload('thumbnail');
        $thumbnail = $newThumbnail ?: $product['thumbnail'];

        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($_POST['name']);

        $this->productModel->update($productId, [
            'category_id'       => (int)$_POST['category_id'],
            'brand_id'          => !empty($_POST['brand_id']) ? (int)$_POST['brand_id'] : null,
            'name'              => trim($_POST['name']),
            'sku'               => strtoupper(trim($_POST['sku'])),
            'slug'              => $slug,
            'price'             => (float)$_POST['price'],
            'sale_price'        => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            'stock_quantity'    => (int)($_POST['stock_quantity'] ?? 0),
            'thumbnail'         => $thumbnail,
            'short_description' => !empty($_POST['short_description']) ? trim($_POST['short_description']) : null,
            'description'       => !empty($_POST['description']) ? trim($_POST['description']) : null,
            'is_featured'       => isset($_POST['is_featured']),
            'is_active'         => isset($_POST['is_active']),
        ]);

        // Update Specs
        $specs = [];
        if (!empty($_POST['spec_names']) && is_array($_POST['spec_names'])) {
            foreach ($_POST['spec_names'] as $i => $name) {
                $val = $_POST['spec_values'][$i] ?? '';
                if (trim($name) !== '' && trim($val) !== '') {
                    $specs[] = ['name' => $name, 'value' => $val];
                }
            }
        }
        $this->productModel->saveSpecs($productId, $specs);

        Session::setFlash('success', 'Cập nhật sản phẩm thành công!');
        Url::redirect('admin/products');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $productId = (int)$id;

        try {
            $ok = $this->productModel->delete($productId);
            if ($ok) {
                Session::setFlash('success', 'Đã xóa sản phẩm thành công.');
            } else {
                Session::setFlash('error', 'Không thể xóa sản phẩm do đang có trong đơn hàng của khách.');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', 'Không thể xóa sản phẩm này vì đã phát sinh giao dịch trong đơn hàng.');
        }

        Url::redirect('admin/products');
    }

    private function handleImageUpload(string $inputName, string $subfolder = 'products'): ?string
    {
        if (empty($_FILES[$inputName]['name']) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$inputName];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedTypes)) {
            Session::setFlash('error', 'Định dạng hình ảnh không hợp lệ (chỉ chấp nhận JPG, PNG, WEBP, GIF).');
            return null;
        }

        // Limit size: 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            Session::setFlash('error', 'Kích thước ảnh không được vượt quá 5MB.');
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        // Sanitize extension to prevent PHP or executable uploads
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $ext = 'jpg';
        }

        $filename = 'prod_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destDir = dirname(__DIR__, 3) . '/public/uploads/' . $subfolder;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $destination = $destDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        return null;
    }
}
