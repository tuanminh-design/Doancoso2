<?php
namespace App;

use App\Helpers\Url;

class Router
{
    private array $routes = [];

    public function get(string $path, string|callable $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    public function post(string $path, string|callable $handler): self
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    private function addRoute(string $method, string $path, string|callable $handler): void
    {
        $path = '/' . trim($path, '/');
        // Convert route pattern with {param} to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $currentUri = $this->getCurrentUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            if (preg_match($route['pattern'], $currentUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->executeHandler($route['handler'], $params);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        $title = 'Không tìm thấy trang - DaNang PC';
        include dirname(__DIR__) . '/views/pages/404.php';
        exit;
    }

    private function getCurrentUri(): string
    {
        // 1. Direct query parameter fallback
        if (isset($_GET['r']) && $_GET['r'] !== '') {
            return '/' . trim($_GET['r'], '/');
        }

        // 2. PATH_INFO
        if (!empty($_SERVER['PATH_INFO'])) {
            return '/' . trim($_SERVER['PATH_INFO'], '/');
        }

        // 3. REQUEST_URI cleaned of script directory and query string
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $pos = strpos($uri, '?');
        if ($pos !== false) {
            $uri = substr($uri, 0, $pos);
        }

        $basePath = Url::basePath();
        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        // Strip index.php if in URL
        $uri = preg_replace('#^/index\.php#', '', $uri);

        $uri = '/' . trim($uri, '/');
        return $uri;
    }

    private function executeHandler(string|callable $handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$controllerName, $action] = explode('@', $handler);
            $fullClass = "App\\Controllers\\" . $controllerName;

            if (!class_exists($fullClass)) {
                die("Controller [{$fullClass}] không tồn tại.");
            }

            $controller = new $fullClass();
            if (!method_exists($controller, $action)) {
                die("Phương thức [{$action}] không tồn tại trong Controller [{$fullClass}].");
            }

            call_user_func_array([$controller, $action], $params);
            return;
        }

        die("Handler không hợp lệ.");
    }
}
