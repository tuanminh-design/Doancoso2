<?php
namespace App\Helpers;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';

            $hosts = array_unique([$config['host'], '127.0.0.1', 'localhost']);
            $port = $config['port'] ?: '3306';
            $dbName = $config['database'] ?: 'pc_store';
            $user = $config['username'] ?: 'root';
            $pass = $config['password'] ?? '';
            $charset = $config['charset'] ?: 'utf8mb4';

            $lastException = null;

            foreach ($hosts as $host) {
                try {
                    $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                    self::$instance = new PDO($dsn, $user, $pass, $config['options']);
                    break; // Connected successfully!
                } catch (PDOException $e) {
                    $lastException = $e;

                    // If error is 1049 (Unknown database), try auto-creating and importing pc_store.sql
                    if ($e->getCode() == 1049 || str_contains($e->getMessage(), 'Unknown database')) {
                        try {
                            $serverDsn = "mysql:host={$host};port={$port};charset={$charset}";
                            $serverPdo = new PDO($serverDsn, $user, $pass, $config['options']);
                            $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                            $sqlFile = dirname(__DIR__, 2) . '/database/pc_store.sql';
                            if (file_exists($sqlFile)) {
                                $sql = file_get_contents($sqlFile);
                                $serverPdo->exec("USE `{$dbName}`");
                                $serverPdo->exec($sql);
                            }

                            // Re-attempt connection to the newly created database
                            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                            self::$instance = new PDO($dsn, $user, $pass, $config['options']);
                            break;
                        } catch (PDOException $createEx) {
                            $lastException = $createEx;
                        }
                    }
                }
            }

            if (self::$instance === null) {
                self::renderConnectionError($lastException, $config);
            }
        }

        return self::$instance;
    }

    private static function renderConnectionError(?PDOException $e, array $config): void
    {
        http_response_code(500);
        $errorMsg = $e ? $e->getMessage() : 'Không thể kết nối đến máy chủ MySQL';
        
        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Lỗi Kết Nối Cơ Sở Dữ Liệu - DaNang PC</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                    background-color: #f1f5f9;
                    color: #0f172a;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    padding: 20px;
                }
                .error-card {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                    max-width: 640px;
                    width: 100%;
                    padding: 40px;
                    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
                    text-align: center;
                }
                .icon-circle {
                    width: 72px;
                    height: 72px;
                    background: #fef2f2;
                    color: #dc2626;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    margin: 0 auto 20px;
                }
                h1 {
                    font-size: 1.45rem;
                    font-weight: 800;
                    color: #0f172a;
                    margin-bottom: 10px;
                }
                p {
                    color: #64748b;
                    font-size: 0.95rem;
                    line-height: 1.6;
                    margin-bottom: 24px;
                }
                .guide-box {
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: left;
                    margin-bottom: 24px;
                    font-size: 0.9rem;
                }
                .guide-box h4 {
                    font-size: 0.95rem;
                    font-weight: 700;
                    color: #1e3a8a;
                    margin-bottom: 12px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .guide-box ol {
                    margin-left: 20px;
                    color: #334155;
                    line-height: 1.8;
                }
                .code-pill {
                    background: #e2e8f0;
                    padding: 2px 6px;
                    border-radius: 4px;
                    font-family: monospace;
                    font-size: 0.85rem;
                }
                .btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: #2563eb;
                    color: #ffffff;
                    text-decoration: none;
                    padding: 12px 28px;
                    border-radius: 8px;
                    font-weight: 700;
                    font-size: 0.95rem;
                    border: none;
                    cursor: pointer;
                    transition: background 0.2s;
                }
                .btn:hover {
                    background: #1d4ed8;
                }
                .error-detail {
                    margin-top: 20px;
                    padding-top: 16px;
                    border-top: 1px solid #f1f5f9;
                    font-size: 0.8rem;
                    color: #94a3b8;
                    font-family: monospace;
                    word-break: break-all;
                }
            </style>
        </head>
        <body>
            <div class="error-card">
                <div class="icon-circle">
                    <i class="fa-solid fa-database"></i>
                </div>
                <h1>Chưa Kết Nối Được Cơ Sở Dữ Liệu MySQL</h1>
                <p>Website không thể kết nối tới dịch vụ MySQL tại cổng <strong>3306</strong>. Hãy kiểm tra dịch vụ MySQL theo hướng dẫn dưới đây:</p>

                <div class="guide-box">
                    <h4><i class="fa-solid fa-circle-question"></i> Hướng Dẫn Bật MySQL Trong XAMPP:</h4>
                    <ol>
                        <li>Mở phần mềm <strong>XAMPP Control Panel</strong> trên máy tính của bạn (đường dẫn: <span class="code-pill">C:\xampp\xampp-control.exe</span>).</li>
                        <li>Tại dòng <strong>MySQL</strong>, bấm nút <strong>Start</strong> (chờ chữ MySQL chuyển sang nền xanh lá).</li>
                        <li>Bấm nút <strong>Thử Lại Kết Nối</strong> bên dưới để tải lại website.</li>
                    </ol>
                </div>

                <button onclick="window.location.reload();" class="btn">
                    <i class="fa-solid fa-arrows-rotate"></i> Thử Lại Kết Nối Ngay
                </button>

                <div class="error-detail">
                    Chi tiết lỗi kỹ thuật: <?= htmlspecialchars($errorMsg) ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
