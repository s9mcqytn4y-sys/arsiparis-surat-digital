<# ─── Starter Template - Init Script (PowerShell) ─────────────────
   Jalankan script ini setelah clone pertama kali.
   Usage: .\init-project.ps1
#>

Write-Host "🚀 Inisialisasi Starter Template..." -ForegroundColor Cyan

# 1. Copy .env
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✅ .env berhasil dibuat dari .env.example" -ForegroundColor Green
} else {
    Write-Host "⏭️  .env sudah ada, dilewati" -ForegroundColor Yellow
}

# 2. Composer install
Write-Host "📦 Menginstal dependensi PHP..." -ForegroundColor Cyan
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Generate app key
Write-Host "🔑 Membuat application key..." -ForegroundColor Cyan
php artisan key:generate --ansi

# 4. Create SQLite database
$dbPath = "database/database.sqlite"
if (-not (Test-Path $dbPath)) {
    New-Item -ItemType File -Path $dbPath -Force | Out-Null
    Write-Host "✅ Database SQLite berhasil dibuat" -ForegroundColor Green
} else {
    Write-Host "⏭️  Database SQLite sudah ada, dilewati" -ForegroundColor Yellow
}

# 5. Run migrations + seed
Write-Host "🗄️  Menjalankan migrasi dan seeder..." -ForegroundColor Cyan
php artisan migrate:fresh --seed --force

# 6. NPM install
Write-Host "📦 Menginstal dependensi Node.js..." -ForegroundColor Cyan
npm install

# 7. Storage link
Write-Host "🔗 Membuat storage symbolic link..." -ForegroundColor Cyan
php artisan storage:link 2>$null

# 8. Clear cache
Write-Host "🧹 Membersihkan cache..." -ForegroundColor Cyan
php artisan optimize:clear

Write-Host ""
Write-Host "═══════════════════════════════════════════" -ForegroundColor Green
Write-Host "  ✅ Starter Template siap digunakan!" -ForegroundColor Green
Write-Host "═══════════════════════════════════════════" -ForegroundColor Green
Write-Host ""
Write-Host "  Jalankan dev server:" -ForegroundColor White
Write-Host "    php artisan serve" -ForegroundColor Yellow
Write-Host "    npm run dev" -ForegroundColor Yellow
Write-Host ""
