<?php

// Membuat folder storage sementara di Vercel agar tidak Error 500
$storageFolders = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
];

foreach ($storageFolders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
}

require __DIR__ . '/../public/index.php';