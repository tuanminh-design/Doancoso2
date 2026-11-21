<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Middleware\AdminMiddleware;
use App\Models\Brand;

class BrandController extends Controller
{
    private Brand $brandModel;

    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
        $this->brandModel = new Brand();
    }

    public function index(): void
    {
        $brands = $this->brandModel->getAll();

        $this->render('admin.brands.index', [
            'pageTitle' => 'Quản Lý Thương Hiệu - DaNang PC Admin',
            'brands'    => $brands,
        ], 'admin');
    }

    public function store(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('name', 'Tên thương hiệu không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/brands');
            return;
        }

        $name = trim($_POST['name']);
        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($name);

        $this->brandModel->create([
            'name' => $name,
            'slug' => $slug,
            'logo' => null,
        ]);

        Session::setFlash('success', 'Thêm thương hiệu mới thành công!');
        Url::redirect('admin/brands');
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $brandId = (int)$id;

        $brand = $this->brandModel->findById($brandId);
        if (!$brand) {
            Session::setFlash('error', 'Thương hiệu không tồn tại.');
            Url::redirect('admin/brands');
            return;
        }

        $validator = Validator::make($_POST);
        $validator->required('name', 'Tên thương hiệu không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/brands');
            return;
        }

        $name = trim($_POST['name']);
        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($name);

        $this->brandModel->update($brandId, [
            'name' => $name,
            'slug' => $slug,
            'logo' => $brand['logo'],
        ]);

        Session::setFlash('success', 'Cập nhật thương hiệu thành công!');
        Url::redirect('admin/brands');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $brandId = (int)$id;

        if ($this->brandModel->hasProducts($brandId)) {
            Session::setFlash('error', 'Không thể xóa thương hiệu này vì vẫn còn sản phẩm thuộc hãng. Vui lòng chuyển hãng cho sản phẩm trước.');
            Url::redirect('admin/brands');
            return;
        }

        $this->brandModel->delete($brandId);
        Session::setFlash('success', 'Đã xóa thương hiệu thành công.');
        Url::redirect('admin/brands');
    }
}
