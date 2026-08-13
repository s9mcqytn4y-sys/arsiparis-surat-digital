#!/usr/bin/env bash
# ─── Starter Template - Init Script (Bash) ──────────────────────
# Jalankan script ini setelah clone pertama kali.
# Usage: bash init-project.sh
set -euo pipefail

echo "🚀 Inisialisasi Starter Template..."

# 1. Copy .env
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✅ .env berhasil dibuat dari .env.example"
else
    echo "⏭️  .env sudah ada, dilewati"
fi

# 2. Composer install
echo "📦 Menginstal dependensi PHP..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Generate app key
echo "🔑 Membuat application key..."
php artisan key:generate --ansi

# 4. Create SQLite database
DB_PATH="database/database.sqlite"
if [ ! -f "$DB_PATH" ]; then
    touch "$DB_PATH"
    echo "✅ Database SQLite berhasil dibuat"
else
    echo "⏭️  Database SQLite sudah ada, dilewati"
fi

# 5. Run migrations + seed
echo "🗄️  Menjalankan migrasi dan seeder..."
php artisan migrate:fresh --seed --force

# 6. NPM install
echo "📦 Menginstal dependensi Node.js..."
npm install

# 7. Storage link
echo "🔗 Membuat storage symbolic link..."
php artisan storage:link 2>/dev/null || true

# 8. Clear cache
echo "🧹 Membersihkan cache..."
php artisan optimize:clear

echo ""
echo "═══════════════════════════════════════════"
echo "  ✅ Starter Template siap digunakan!"
echo "═══════════════════════════════════════════"
echo ""
echo "  Jalankan dev server:"
echo "    php artisan serve"
echo "    npm run dev"
echo ""
