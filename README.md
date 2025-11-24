# ISEKI - Organization Chart Management System

A modern, feature-rich web application for creating, managing, and displaying organizational charts (Bagan). Built with Laravel, Livewire, and powered by a sleek Tailwind CSS interface.

## 📋 Overview

ISEKI (formerly BAGAN-WEB) is a comprehensive organizational chart management system that allows users to visualize hierarchical structures within organizations. The application provides both public viewing capabilities and a robust admin panel for chart creation and management.

## ✨ Features

- **📊 Interactive Organization Charts**: Display hierarchical organizational structures with an intuitive, interactive interface
- **🎨 Modern UI/UX**: Built with Tailwind CSS 4 and Alpine.js for a responsive, beautiful user experience
- **🌓 Dark Mode Support**: Fully functional dark mode for comfortable viewing in any environment
- **👥 Multi-Chart Management**: Create and manage multiple organizational charts simultaneously
- **🔐 Admin Panel**: Secure authentication system with comprehensive chart management capabilities
- **⚡ Real-time Updates**: Powered by Livewire for dynamic, real-time user interactions
- **📱 Responsive Design**: Works seamlessly across desktop, tablet, and mobile devices
- **🎯 Chart Editor**: Intuitive interface for creating and editing organizational structures
- **🔗 Link Management**: Support for different types of connections and relationships within charts

## 🛠️ Technology Stack

### Backend
- **Laravel 12**: Modern PHP framework
- **Livewire**: Full-stack framework for dynamic interfaces
- **Volt**: Functional API for Livewire
- **PHP 8.2+**: Latest PHP features and performance

### Frontend
- **Tailwind CSS 4**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **OrgChart.js**: Professional organization chart library (included as static asset)
- **Vite**: Modern build tool and development server
- **Lucide Icons**: Beautiful, consistent icon set

### Database
- **SQLite**: Default database (configurable to MySQL/PostgreSQL)

### Development Tools
- **Laravel Pint**: PHP code style fixer
- **Pest**: Modern testing framework
- **Laravel Debugbar**: Development debugging tool
- **Laravel Sail**: Docker development environment

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/TUNBudi06/BAGAN-WEB.git
   cd BAGAN-WEB
   ```

2. **Install dependencies**
   ```bash
   composer setup
   ```
   This command will:
   - Install PHP dependencies
   - Create `.env` file from `.env.example`
   - Generate application key
   - Run database migrations
   - Install Node.js dependencies
   - Build frontend assets

### Manual Setup

If you prefer manual installation:

1. **Install PHP dependencies**
   ```bash
   composer install
   ```

2. **Create environment file**
   ```bash
   cp .env.example .env
   ```

3. **Generate application key**
   ```bash
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file and set your database credentials (SQLite is configured by default)

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Install Node.js dependencies**
   ```bash
   npm install
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

## 🚀 Usage

### Development Server

Start the development server with all necessary services:

```bash
composer dev
```

This command will start:
- Laravel development server (http://localhost:8000)
- Queue listener
- Vite development server (with hot module replacement)

The application will be available at `http://localhost:8000`

### Building for Production

Compile and minify assets for production:

```bash
npm run build
```

### Running Tests

Execute the test suite:

```bash
composer test
```

Or directly with Pest:

```bash
php artisan test
```

## 🎯 Project Structure

```
BAGAN-WEB/
├── app/
│   ├── Http/
│   │   └── Controllers/      # Application controllers
│   │       ├── AdminController.php
│   │       ├── BaseRoute.php
│   │       └── ChartDesigner.php
│   └── Models/               # Eloquent models
│       ├── BaganList.php
│       ├── SubLevels.php
│       └── ...
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── css/                 # Stylesheets
│   ├── js/                  # JavaScript files
│   └── views/               # Blade templates
│       ├── AdminPages/      # Admin panel views
│       ├── Base/            # Public chart views
│       ├── livewire/        # Livewire components
│       └── Account/         # Authentication views
├── routes/
│   └── web.php              # Web routes
├── public/                  # Public assets
├── tests/                   # Test files
├── composer.json            # PHP dependencies
├── package.json             # Node.js dependencies
└── vite.config.js          # Vite configuration
```

## 🔑 Key Routes

- `/` - Home page (redirects to first available chart)
- `/base/{id}` - View specific organizational chart
- `/account/login` - Login page
- `/admin/dashboard` - Admin dashboard (requires authentication)
- `/admin/BaganList` - Manage organization charts
- `/admin/BaganeEdit/{id}` - Edit specific chart
- `/admin/UserSettings` - User settings

## 💾 Database Schema

The application uses several key tables:

- `bagan_lists` - Stores organizational chart metadata
- `sub_levels` - Hierarchical levels within charts
- `link_chart_embeds` - Chart connections and relationships
- `users` - User authentication and management

## 🎨 Customization

### Styling
The application uses Tailwind CSS 4. Customize styles in:
- `resources/css/` - CSS files
- `tailwind.config.js` - Tailwind configuration

### Configuration
- `.env` - Environment variables
- `config/` - Laravel configuration files

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

## 🔧 Troubleshooting

### Common Issues

**Database not found**
```bash
php artisan migrate:fresh
```

**Assets not loading**
```bash
npm run build
```

**Permission errors**
```bash
chmod -R 775 storage bootstrap/cache
```

## 📧 Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/TUNBudi06/BAGAN-WEB).

---

Built with ❤️ using Laravel and modern web technologies.
