@echo off
echo Starting Laravel development server and Vite...
echo.
echo Open two terminals:
echo 1. Run: php artisan serve
echo 2. Run: npm run dev
echo.
echo Or use this script to start both automatically...
echo.

start "Laravel Server" cmd /k "php artisan serve"
timeout /t 2 /nobreak >nul
start "Vite Dev Server" cmd /k "npm run dev"

echo.
echo Both servers are starting...
echo Laravel: http://127.0.0.1:8000
echo Vite: http://localhost:5173
echo.
pause





