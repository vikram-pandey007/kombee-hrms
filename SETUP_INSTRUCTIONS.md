# HRMS System Setup Instructions

## Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js and npm
- Laragon (recommended) or XAMPP

## Database Setup

1. **Create Database**
   - Open phpMyAdmin or your MySQL client
   - Create a new database named `hrms`

2. **Configure Environment**
   - Copy `.env.example` to `.env`
   - Update the database configuration in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hrms
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```

## Installation Steps

### Option 1: Using the Setup Script (Windows)
1. Double-click `setup.bat` to run the automated setup
2. Follow the prompts

### Option 2: Manual Setup
1. **Install PHP Dependencies**
   ```bash
   composer install
   ```

2. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

3. **Run Migrations and Seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Install Node.js Dependencies**
   ```bash
   npm install
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

## Starting the Application

### Using Laragon
- Start Laragon
- The application will be available at `http://hrms-system.test` (or your configured domain)

### Using PHP Development Server
```bash
php artisan serve
```
- Visit `http://localhost:8000`

## Default Login Credentials

After running the seeders, you can log in with:
- **Email:** admin@example.com
- **Password:** password

## Features Implemented

### ✅ Employee Management
- Add, edit, delete employees
- Dynamic department and designation dropdowns
- Employee status management
- Search and filter functionality
- Proper validation and error handling

### ✅ Leave Management
- Create, edit, delete leave requests
- Approve/reject leave requests
- Multiple leave types (Casual, Sick, Annual, Personal, Other)
- Duration calculation
- Search and filter by employee/status
- Proper validation and error handling

### ✅ Payroll Management
- Generate payroll for all employees
- Generate individual payroll with custom deductions
- View detailed payslips
- Multiple payroll statuses (Pending, Processed, Paid, Cancelled)
- Leave deductions calculation
- Search and filter functionality
- Proper validation and error handling

### ✅ Enhanced Features
- **Proper Error Handling**: All modules now have comprehensive error handling with user-friendly messages
- **Validation**: Robust validation rules for all forms
- **Database Transactions**: All database operations use transactions for data integrity
- **Logging**: Comprehensive logging for debugging and monitoring
- **Responsive Design**: Modern UI with Tailwind CSS
- **Dark Mode Support**: Full dark mode compatibility
- **Auto-hide Messages**: Success and error messages auto-hide after a few seconds

## Database Structure

### Tables Created
- `users` - User authentication
- `departments` - Company departments
- `designations` - Job designations
- `employees` - Employee records with foreign keys to departments and designations
- `leaves` - Leave requests
- `payrolls` - Payroll records with detailed deductions

### Seeded Data
- Sample departments (IT, HR, Finance, Marketing, Operations)
- Sample designations (Manager, Developer, Analyst, Coordinator, Specialist)
- Sample employees with realistic data
- Sample leave requests
- Sample payroll records

## Troubleshooting

### Database Connection Issues
1. Check if MySQL service is running
2. Verify database credentials in `.env`
3. Ensure database `hrms` exists
4. Check if MySQL driver is enabled in PHP

### Migration Issues
1. Clear cache: `php artisan cache:clear`
2. Clear config: `php artisan config:clear`
3. Re-run migrations: `php artisan migrate:fresh --seed`

### Asset Issues
1. Clear npm cache: `npm cache clean --force`
2. Delete node_modules: `rm -rf node_modules`
3. Reinstall: `npm install`
4. Rebuild: `npm run build`

## Support

If you encounter any issues:
1. Check the Laravel logs in `storage/logs/laravel.log`
2. Ensure all prerequisites are installed
3. Verify database connectivity
4. Check file permissions on storage and bootstrap/cache directories 