<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Auth;
use App\Helpers\Format;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Helpers\Validator;
use App\Middleware\AdminMiddleware;
use App\Models\News;

class NewsController extends Controller
{
    private News $newsModel;

    public function __construct()
    {
        parent::__construct();
        AdminMiddleware::handle();
        $this->newsModel = new News();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $filters = [
            'search'          => !empty($_GET['q']) ? trim($_GET['q']) : null,
            'include_drafts'  => true,
        ];

        $articles = $this->newsModel->getFiltered($filters, $limit, $offset);
        $totalArticles = $this->newsModel->countFiltered($filters);
        $totalPages = max(1, (int)ceil($totalArticles / $limit));

        $this->render('admin.news.index', [
            'pageTitle'     => 'Quản Lý Bài Viết Công Nghệ - DaNang PC Admin',
            'articles'      => $articles,
            'totalArticles' => $totalArticles,
            'page'          => $page,
            'totalPages'    => $totalPages,
            'search'        => $filters['search'],
        ], 'admin');
    }

    public function create(): void
    {
        $categories = $this->newsModel->getCategories();

        $this->render('admin.news.create', [
            'pageTitle'  => 'Thêm Bài Viết Công Nghệ Mới - DaNang PC Admin',
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void
    {
        $this->validateCsrf();

        $validator = Validator::make($_POST);
        $validator->required('title', 'Tiêu đề bài viết không được để trống.')
                  ->required('summary', 'Tóm tắt bài viết không được để trống.')
                  ->required('content', 'Nội dung bài viết không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/news/create');
            return;
        }

        $thumbnail = $this->handleImageUpload('thumbnail');
        $title = trim($_POST['title']);
        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($title);

        $this->newsModel->create([
            'category_id'  => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'user_id'      => Auth::id(),
            'title'        => $title,
            'slug'         => $slug,
            'thumbnail'    => $thumbnail,
            'summary'      => trim($_POST['summary']),
            'content'      => trim($_POST['content']),
            'author_name'  => !empty($_POST['author_name']) ? trim($_POST['author_name']) : Auth::name(),
            'is_published' => isset($_POST['is_published']),
        ]);

        Session::setFlash('success', 'Thêm bài viết mới thành công!');
        Url::redirect('admin/news');
    }

    public function edit(string $id): void
    {
        $article = $this->newsModel->findById((int)$id);
        if (!$article) {
            Session::setFlash('error', 'Bài viết không tồn tại.');
            Url::redirect('admin/news');
            return;
        }

        $categories = $this->newsModel->getCategories();

        $this->render('admin.news.edit', [
            'pageTitle'  => 'Chỉnh Sửa Bài Viết: ' . $article['title'],
            'article'    => $article,
            'categories' => $categories,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->validateCsrf();
        $articleId = (int)$id;

        $article = $this->newsModel->findById($articleId);
        if (!$article) {
            Session::setFlash('error', 'Bài viết không tồn tại.');
            Url::redirect('admin/news');
            return;
        }

        $validator = Validator::make($_POST);
        $validator->required('title', 'Tiêu đề không được để trống.')
                  ->required('summary', 'Tóm tắt không được để trống.')
                  ->required('content', 'Nội dung không được để trống.');

        if ($validator->fails()) {
            Session::setFlash('error', implode('<br>', $validator->errors()));
            Url::redirect('admin/news/edit/' . $articleId);
            return;
        }

        $newThumb = $this->handleImageUpload('thumbnail');
        $thumbnail = $newThumb ?: $article['thumbnail'];

        $title = trim($_POST['title']);
        $slug = !empty($_POST['slug']) ? Format::slug($_POST['slug']) : Format::slug($title);

        $this->newsModel->update($articleId, [
            'category_id'  => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'title'        => $title,
            'slug'         => $slug,
            'thumbnail'    => $thumbnail,
            'summary'      => trim($_POST['summary']),
            'content'      => trim($_POST['content']),
            'author_name'  => !empty($_POST['author_name']) ? trim($_POST['author_name']) : $article['author_name'],
            'is_published' => isset($_POST['is_published']),
        ]);

        Session::setFlash('success', 'Cập nhật bài viết thành công!');
        Url::redirect('admin/news');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $articleId = (int)$id;

        $this->newsModel->delete($articleId);
        Session::setFlash('success', 'Đã xóa bài viết thành công.');
        Url::redirect('admin/news');
    }

    private function handleImageUpload(string $inputName): ?string
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
            Session::setFlash('error', 'Định dạng hình ảnh không hợp lệ.');
            return null;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            Session::setFlash('error', 'Kích thước ảnh không được vượt quá 5MB.');
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $ext = 'jpg';
        }

        $filename = 'news_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destDir = dirname(__DIR__, 3) . '/public/uploads/news';

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
