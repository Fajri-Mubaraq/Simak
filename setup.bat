@echo off
echo ================================================
echo   SIMAK - Setup Database ^& Migration
echo ================================================
echo.

REM Cari MySQL Laragon
set MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe

if not exist "%MYSQL_PATH%" (
    echo [ERROR] MySQL tidak ditemukan di: %MYSQL_PATH%
    echo Pastikan Laragon sudah diinstall dan MySQL sudah dijalankan.
    pause
    exit /b 1
)

echo [1/4] Membuat database db_simak...
"%MYSQL_PATH%" -u root -e "CREATE DATABASE IF NOT EXISTS db_simak CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 (
    echo [ERROR] Gagal membuat database. Pastikan Laragon MySQL sudah berjalan!
    pause
    exit /b 1
)
echo     Database db_simak berhasil dibuat.

echo.
echo [2/4] Menjalankan migrasi...
php artisan migrate --force
if errorlevel 1 (
    echo [ERROR] Migrasi gagal!
    pause
    exit /b 1
)

echo.
echo [3/4] Menjalankan seeder...
php artisan db:seed --force
if errorlevel 1 (
    echo [ERROR] Seeder gagal!
    pause
    exit /b 1
)

echo.
echo [4/4] Membersihkan cache...
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo ================================================
echo   SETUP BERHASIL!
echo ================================================
echo.
echo   Login ke aplikasi dengan:
echo   Email    : admin@simak.ac.id
echo   Password : admin123
echo.
echo   Jalankan server: php artisan serve
echo   Lalu buka: http://localhost:8000
echo.
pause
