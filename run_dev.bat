@echo off
title DaNang PC - Dev Server
color 0A

echo ===================================================
echo     DANANG PC - LOCAL DEVELOPMENT ENVIRONMENT
echo ===================================================
echo.

:: 1. Kiem tra MySQL da bat chua
netstat -ano | findstr :3306 >nul
if %errorlevel% neq 0 (
    echo [*] MySQL chua chay. Dang khoi dong MySQL tu XAMPP...
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        pushd "C:\xampp"
        start "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
        popd
        timeout /t 3 >nul
        echo [OK] Da bat MySQL thanh cong.
    ) else (
        echo [!] Khong tim thay C:\xampp\mysql\bin\mysqld.exe.
        echo [*] Vui long mo XAMPP Control Panel va bam Start MySQL.
    )
) else (
    echo [OK] MySQL dang chay tren cong 3306.
)

:: 2. Tu dong kiem tra va tao database pc_store neu chua co
if exist "C:\xampp\mysql\bin\mysql.exe" (
    "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS pc_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
    "C:\xampp\mysql\bin\mysql.exe" -u root -e "USE pc_store; SELECT COUNT(*) FROM products;" >nul 2>&1
    if %errorlevel% neq 0 (
        echo [*] Dang khoi tao du lieu mau pc_store.sql...
        "C:\xampp\mysql\bin\mysql.exe" -u root pc_store < "%~dp0database\pc_store.sql"
        echo [OK] Da import co so du lieu mau thanh cong!
    )
)

echo.
echo ===================================================
echo  WEBSITE DANG CHAY TAI: http://localhost:8080
echo  Thao tac:
echo   - Mo trinh duyet vao: http://localhost:8080
echo   - Trang quan tri:     http://localhost:8080/admin/login
echo                         (admin / 123456)
echo  Luu y: Giu nguyen cua so nay khi dung web.
echo         Nhan Ctrl + C de tat server khi xong.
echo ===================================================
echo.

:: Mo trinh duyet tu dong
start http://localhost:8080

:: Khoi dong PHP Built-in Server
if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" -S 127.0.0.1:8080 -t "%~dp0public"
) else (
    php -S 127.0.0.1:8080 -t "%~dp0public"
)

pause
