<?php

declare(strict_types=1);

// Initialize /tmp directories for serverless environments (read-only filesystem workaround)
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Redirect storage and bootstrap cache paths to /tmp in Vercel Serverless environment
if (getenv('VERCEL') || getenv('AWS_LAMBDA_FUNCTION_NAME')) {
    if (! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';
    }

    putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
    putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
    putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
    putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
    putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
    putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

    // Ensure APP_KEY exists in serverless runtime
    if (! getenv('APP_KEY')) {
        putenv('APP_KEY=base64:cWcxeUhkOWx6a05lWnJXZ3V3Z0lUeWVwNm5sU0xScEE=');
    }

    // Prepare SQLite database in /tmp if external pgsql is not configured
    if (! getenv('DATABASE_URL') && (! getenv('DB_CONNECTION') || getenv('DB_CONNECTION') === 'sqlite')) {
        $targetSqlite = '/tmp/database.sqlite';
        if (! file_exists($targetSqlite)) {
            $sourceSqlite = __DIR__.'/../database/database.sqlite';
            if (file_exists($sourceSqlite)) {
                @copy($sourceSqlite, $targetSqlite);
            } else {
                @touch($targetSqlite);
            }
        }
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE='.$targetSqlite);
    }
}

// Forward execution to Laravel public entrypoint
require __DIR__.'/../public/index.php';
