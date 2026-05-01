<?php

// 1. Tampilkan error secara paksa (hanya untuk debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Load Autoload
require __DIR__ . '/../vendor/autoload.php';

// 3. Pindahkan folder storage ke /tmp (Vercel bersifat read-only)
$storagePaths = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/logs',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

// 4. Paksa Laravel mengabaikan cache config dari laptop
putenv('APP_CONFIG_CACHE=' . '/tmp/config.php');
putenv('APP_ROUTES_CACHE=' . '/tmp/routes.php');
putenv('APP_SERVICES_CACHE=' . '/tmp/services.php');
putenv('APP_PACKAGES_CACHE=' . '/tmp/packages.php');

// 5. Jalankan Aplikasi
require __DIR__ . '/../public/index.php';