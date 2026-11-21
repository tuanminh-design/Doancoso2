<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Middleware\AdminMiddleware;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getAll();

        $this->render('admin.categories.index', [
            'pageTitle'  => 'Quản Lý Danh Mục Linh Kiện - DaNang PC Admin',
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('name', 'Tên danh mục không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/categories');
            return;
        }

        $name = trim($_POST['name']);
        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($name);

        $this->categoryModel->create([
            'name'        => $name,
            'slug'        => $slug,
            'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
            'icon'        => !empty($_POST['icon']) ? trim($_POST['icon']) : 'fa-microchip',
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'is_active'   => isset($_POST['is_active']),
        ]);

        Session::setFlash('success', 'Thêm danh mục linh kiện mới thành công!');
        Url::redirect('admin/categories');
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $catId = (int)$id;

        $category = $this->categoryModel->findById($catId);
        if (!$category) {
            Session::setFlash('error', 'Danh mục không tồn tại.');
            Url::redirect('admin/categories');
            return;
        }

        $validator = Validator::make($_POST);
        $validator->required('name', 'Tên danh mục không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/categories');
            return;
        }

        $name = trim($_POST['name']);
        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($name);

        $this->categoryModel->update($catId, [
            'name'        => $name,
            'slug'        => $slug,
            'description' => !empty($_POST['description']) ? trim($_POST['description']) : null,
            'icon'        => !empty($_POST['icon']) ? trim($_POST['icon']) : 'fa-microchip',
            'sort_order'  => (int)($_POST['sort_order'] ?? 0),
            'is_active'   => isset($_POST['is_active']),
        ]);

        Session::setFlash('success', 'Cập nhật danh mục thành công!');
        Url::redirect('admin/categories');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $catId = (int)$id;

        if ($this->categoryModel->hasProducts($catId)) {
            Session::setFlash('error', 'Không thể xóa danh mục này vì vẫn còn sản phẩm thuộc danh mục. Vui lòng chuyển hoặc xóa sản phẩm trước.');
            Url::redirect('admin/categories');
            return;
        }

        $this->categoryModel->delete($catId);
        Session::setFlash('success', 'Đã xóa danh mục thành công.');
        Url::redirect('admin/categories');
    }
}
