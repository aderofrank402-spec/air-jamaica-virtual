<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the reCAPTCHA configuration
$config = [
    'enabled' => (bool) setting('captcha.enabled', false),
    'site_key' => setting('captcha.site_key', ''),
    'secret_key' => setting('captcha.secret_key', '')
];

// Set JSON headers
header('Content-Type: application/json');
echo json_encode($config);