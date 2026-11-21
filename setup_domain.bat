@echo off
title Cai dat ten mien DanangPC.vn
color 0B

echo ===================================================
echo     CAI DAT TEN MIEN AO: http://danangpc.vn
echo ===================================================
echo.

:: Kiem tra quyen Administrator
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo [*] Dang yeu cau quyen Administrator de chinh sua file hosts...
    powershell -NoProfile -Command "Start-Process cmd.exe -ArgumentList '/c \"\"%~f0\"\"' -Verb RunAs"
    exit /b
)

echo [*] Dang them ten mien danangpc.vn vao C:\Windows\System32\drivers\etc\hosts...
set HOSTS=%WINDIR%\System32\drivers\etc\hosts

findstr /i "danangpc.vn" "%HOSTS%" >nul
if %errorlevel% neq 0 (
    echo. >> "%HOSTS%"
    echo 127.0.0.1 danangpc.vn www.danangpc.vn danangpc.local >> "%HOSTS%"
    echo [OK] Da them thanh cong vao file hosts:
    echo      127.0.0.1 danangpc.vn www.danangpc.vn danangpc.local
) else (
    echo [OK] Ten mien danangpc.vn da co san trong file hosts.
)

echo.
echo ===================================================
echo  HOAN TAT CAI DAT TEN MIEN!
echo.
echo  Cac buoc su dung voi XAMPP:
echo  1. Mo XAMPP Control Panel
echo  2. Bam "Start" Apache va "Start" MySQL
echo  3. Mo trinh duyet truy cap: http://danangpc.vn
echo     (Trang quan tri: http://danangpc.vn/admin/login)
echo ===================================================
echo.
pause
