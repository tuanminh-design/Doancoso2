<?php
namespace App\Controllers;

use App\Helpers\Url;
use App\Models\News;

class NewsController extends Controller
{
    private News $newsModel;

    public function __construct()
    {
        parent::__construct();
        $this->newsModel = new News();
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $filters = [
            'category_slug' => !empty($_GET['category']) ? trim($_GET['category']) : null,
            'search'        => !empty($_GET['q']) ? trim($_GET['q']) : null,
        ];

        $articles = $this->newsModel->getFiltered($filters, $limit, $offset);
        $totalArticles = $this->newsModel->countFiltered($filters);
        $totalPages = max(1, (int)ceil($totalArticles / $limit));

        $newsCategories = $this->newsModel->getCategories();
        $latestArticles = $this->newsModel->getLatest(5);

        $activeCategory = null;
        if (!empty($filters['category_slug'])) {
            $activeCategory = $this->newsModel->getCategoryBySlug($filters['category_slug']);
        }

        $pageTitle = 'Tin Tức Công Nghệ & Phần Cứng PC - DaNang PC';
        if ($activeCategory) {
            $pageTitle = $activeCategory['name'] . ' - DaNang PC';
        }

        $this->render('news.index', [
            'pageTitle'      => $pageTitle,
            'articles'       => $articles,
            'totalArticles'  => $totalArticles,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'filters'        => $filters,
            'newsCategories' => $newsCategories,
            'latestArticles' => $latestArticles,
            'activeCategory' => $activeCategory,
        ]);
    }

    public function detail(string $slug): void
    {
        $article = $this->newsModel->findBySlug($slug);
        if (!$article) {
            http_response_code(404);
            $this->render('pages.404', ['pageTitle' => 'Bài viết không tồn tại']);
            return;
        }

        $this->newsModel->incrementView($article['id']);
        $latestArticles = $this->newsModel->getLatest(5);
        $newsCategories = $this->newsModel->getCategories();

        $this->render('news.detail', [
            'pageTitle'      => $article['title'] . ' - DaNang PC',
            'article'        => $article,
            'latestArticles' => $latestArticles,
            'newsCategories' => $newsCategories,
        ]);
    }
}
