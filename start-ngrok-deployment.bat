@echo off
echo.
echo ================================================
echo    Ngrok Deployment Setup for Laravel App
echo ================================================
echo.

REM Check if ngrok is installed
where ngrok >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: ngrok is not installed or not in PATH.
    echo Please install ngrok from https://ngrok.com/download
    echo Then add it to your system PATH.
    echo.
    pause
    exit /b 1
)

REM Check if PHP is installed
where php >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: PHP is not installed or not in PATH.
    echo Please install PHP and add it to your system PATH.
    echo.
    pause
    exit /b 1
)

REM Check if Laravel is properly configured
if not exist "artisan" (
    echo ERROR: Laravel artisan file not found.
    echo Make sure you are in the correct Laravel project directory.
    echo.
    pause
    exit /b 1
)

REM Generate app key if not exists
if "%APP_KEY%"=="" (
    echo Generating application key...
    php artisan key:generate --force
)

echo Starting Laravel development server...
start "Laravel Server" cmd /c "php artisan serve --host=127.0.0.1 --port=8000"

echo Waiting for Laravel server to start...
timeout /t 10 /nobreak >nul

echo.
echo Starting Ngrok tunnel to port 8000...
echo Your application will be available at the URL shown below:
echo.
ngrok http 8000

echo.
echo Ngrok tunnel stopped.
echo To restart, run this batch file again.
echo.
pause