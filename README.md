# HRMS - Human Resource Management System

A modern, feature-rich Human Resource Management System built with Laravel 11, Livewire, and Tailwind CSS.

## 🚀 Features

### 👥 Employee Management
- Add, edit, and manage employee information
- Employee search and filtering by department/designation
- Employee status management (Active/Inactive)
- Automatic employee ID generation
- Employee profile with complete details

### 📅 Leave Management
- Submit and manage leave requests
- Multiple leave types (Paid, Sick, Casual, Unpaid)
- Leave approval/rejection workflow
- Leave history tracking
- Duration calculation

### 💰 Payroll Management
- Automated payroll generation
- Salary calculation with deductions
- Leave-based salary deductions
- Payroll status tracking (Paid/Unpaid)
- Payslip generation and viewing

### 📊 Dashboard
- Real-time statistics and metrics
- Quick access to all modules
- Visual data representation
- Employee count, leave requests, payroll overview

### 🔐 Authentication & Security
- Laravel Breeze authentication
- User profile management
- Secure password handling
- Role-based access (ready for expansion)

## 🛠️ Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Tables**: PowerGrid for data tables
- **Authentication**: Laravel Breeze

## 📋 Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+ or PostgreSQL 13+
- Node.js & NPM

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hrms-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hrms_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👤 Default Users

After running the seeders, you can login with:

- **Admin User**
  - Email: `admin@hrms.com`
  - Password: `password`

- **HR Manager**
  - Email: `hr@hrms.com`
  - Password: `password`

- **Finance Manager**
  - Email: `finance@hrms.com`
  - Password: `password`

## 📁 Project Structure

```
hrms-system/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Livewire/            # Livewire components
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   │   ├── livewire/        # Livewire views
│   │   └── layouts/         # Layout templates
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript
└── routes/                  # Route definitions
```

## 🔧 Configuration

### Database Configuration
The system uses Laravel's standard database configuration. Make sure to:
- Set up your database connection in `.env`
- Run migrations to create tables
- Seed the database with initial data

### Mail Configuration (Optional)
For email notifications, configure your mail settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hrms.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🎨 UI/UX Features

- **Responsive Design**: Works on desktop, tablet, and mobile
- **Dark Mode Support**: Toggle between light and dark themes
- **Modern Interface**: Clean, professional design with Tailwind CSS
- **Interactive Tables**: Sortable, searchable, and filterable data tables
- **Real-time Updates**: Livewire components for dynamic interactions
- **Accessibility**: WCAG compliant design elements

## 🔍 Key Components

### Livewire Components
- `Dashboard`: Main dashboard with statistics
- `EmployeeManager`: Employee CRUD operations
- `LeaveManager`: Leave request management
- `PayrollManager`: Payroll processing and management

### PowerGrid Tables
- `EmployeeTable`: Employee listing with actions
- `LeaveTable`: Leave requests with approval/rejection
- `PayrollTable`: Payroll records with status management

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Ensure proper file permissions
6. Configure web server (Apache/Nginx)

### Environment Variables
```env
APP_NAME="HR Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrms_production
DB_USERNAME=production_user
DB_PASSWORD=secure_password
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Check the documentation
- Review the Laravel and Livewire documentation

## 🔄 Updates

### Recent Updates
- ✅ Fixed action buttons styling in data tables
- ✅ Added proper logout functionality
- ✅ Improved navigation design
- ✅ Enhanced dark mode support
- ✅ Fixed PowerGrid table configurations
- ✅ Added comprehensive data seeders
- ✅ Improved responsive design
- ✅ Added data-testid attributes for testing

### Planned Features
- [ ] Email notifications for leave approvals
- [ ] Advanced reporting and analytics
- [ ] Document management system
- [ ] Time tracking integration
- [ ] Performance review system
- [ ] Multi-language support
- [ ] API endpoints for mobile apps
- [ ] Advanced role-based permissions

---

**Built with ❤️ using Laravel 11, Livewire 3, and Tailwind CSS**
