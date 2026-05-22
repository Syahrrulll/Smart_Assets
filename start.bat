@echo off

:: Buka Cloudflare di window baru (Async)
start "Online Local IP" cmd /k "start_cloudfare.bat"

:: Jalankan Laravel serve di window ini (Sync)
php artisan serve --host=0.0.0.0 
