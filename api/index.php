<?php

// Paksa agar semua error tampil ke log Vercel
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan path ke vendor/autoload benar
require __DIR__ . '/../vendor/autoload.php';

// Pindahkan storage ke /tmp (Wajib di Vercel)
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

// Eksekusi Laravel
require __DIR__ . '/../public/index.php';