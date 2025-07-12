@echo off
echo ========================================
echo HRMS System Setup Script
echo ========================================
echo.

echo Step 1: Checking if .env file exists...
if not exist ".env" (
    echo Creating .env file from .env.example...
    copy .env.example .env
    echo .env file created successfully!
) else (
    echo .env file already exists.
)
echo.

echo Step 2: Generating application key...
php artisan key:generate
echo.

echo Step 3: Running database migrations...
php artisan migrate:fresh --seed
echo.

echo Step 4: Installing npm dependencies...
npm install
echo.

echo Step 5: Building assets...
npm run build
echo.

echo ========================================
echo Setup completed successfully!
echo ========================================
echo.
echo You can now start the development server with:
echo php artisan serve
echo.
echo Or visit your Laragon URL if using Laragon.
echo.
pause 