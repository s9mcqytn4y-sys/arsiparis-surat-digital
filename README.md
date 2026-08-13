# Laravel Vibe Starter 🚀

A modern, robust, and zero-trust engineered **Laravel 13.x** starter template designed for rapid web application development. Built with strict **Vibe Coding** architectural guidelines and optimized for the 2026 engineering standards.

## 🌟 Tech Stack
- **Framework**: Laravel 13.x, PHP 8.5+
- **Frontend & Admin**: Filament 3.x, Tailwind CSS v4, Livewire 3
- **Database**: SQLite (Local Default) -> PostgreSQL/MySQL (Production). *Primary keys use UUID v7*.
- **Auth & Security**: Spatie Permission, Spatie Activitylog.
- **Testing**: Pest v5 (Feature & Unit).

## 🚀 Features
- **Zero Trust Security**: Strict IAM, OIDC/JWT handling, BOLA/IDOR defenses.
- **Vibe Coding Ready**: Pre-configured `settings.json` for VS Code, PSR-12/PER CS 2.0 standards, and strict typing (`declare(strict_types=1)`).
- **Environment Boundaries**: strict `.env` hierarchy, secret management rules, and configuration caching.
- **Filament Admin Panel**: Out-of-the-box admin panel at `/admin` with unified login redirection.

## 🛠️ Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/s9mcqytn4y-sys/laravel-starter.git
cd laravel-starter

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Run migrations
php artisan migrate:fresh --seed

# 5. Start development servers
php artisan serve
npm run dev
```

## 📜 Architecture Directives
Please refer to `GEMINI.md` for comprehensive architectural guidelines, security matrix, and execution directives expected from developers and AI agents operating on this codebase.

## 📄 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

