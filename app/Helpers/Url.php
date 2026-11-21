<?php
namespace App\Helpers;

class Url
{
    private static ?string $basePath = null;

    public static function basePath(): string
    {
        if (self::$basePath === null) {
            // Determine the subfolder path from SCRIPT_NAME
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $dir = str_replace('\\', '/', dirname($scriptName));
            // Remove /public if present at the end of dir to allow clean paths or handle both
            $cleanDir = rtrim($dir, '/');
            self::$basePath = $cleanDir;
        }
        return self::$basePath;
    }

    public static function to(string $path = '', array $params = []): string
    {
        $base = self::basePath();
        $path = ltrim($path, '/');
        
        $url = $base . ($path ? '/' . $path : '');
        if (empty($url)) {
            $url = '/';
        }

        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= (str_contains($url, '?') ? '&' : '?') . $query;
        }

        return $url;
    }

    public static function asset(string $path): string
    {
        $path = ltrim($path, '/');
        $base = self::basePath();
        return $base . '/assets/' . $path;
    }

    public static function upload(?string $filename, string $folder = 'products'): string
    {
        $base = self::basePath();
        if (empty($filename)) {
            return $base . '/assets/images/placeholders/no-image.svg';
        }

        // If it's already an absolute URL or data URL
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://') || str_starts_with($filename, 'data:')) {
            return $filename;
        }

        // Check if file physically exists in uploads
        $realPath = dirname(__DIR__, 2) . '/public/uploads/' . $folder . '/' . $filename;
        if (file_exists($realPath)) {
            return $base . '/uploads/' . $folder . '/' . $filename;
        }

        // Check in public/assets/images
        $assetPath = dirname(__DIR__, 2) . '/public/assets/images/' . $filename;
        if (file_exists($assetPath)) {
            return $base . '/assets/images/' . $filename;
        }

        // Check SVG equivalent in public/assets/images
        $svgName = preg_replace('/\.(jpg|jpeg|png)$/i', '.svg', $filename);
        $svgPath = dirname(__DIR__, 2) . '/public/assets/images/' . $svgName;
        if (file_exists($svgPath)) {
            return $base . '/assets/images/' . $svgName;
        }

        return $base . '/assets/images/placeholders/no-image.svg';
    }

    public static function redirect(string $url): void
    {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = self::to($url);
        }
        header('Location: ' . $url);
        exit;
    }

    public static function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? self::to('/');
        header('Location: ' . $referer);
        exit;
    }
}
