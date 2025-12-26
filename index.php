<?php

// DEBUGGING: Enable error reporting to see what's wrong
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/core/router.php';
require_once __DIR__ . '/core/sitemap-generator.php';

// Basic environment variable loading
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Determine Base URL
// Use APP_URL from .env if available (Production), otherwise fallback to auto-detection (Dev)
if (isset($_ENV['APP_URL']) && filter_var($_ENV['APP_URL'], FILTER_VALIDATE_URL)) {
    $baseUrl = rtrim($_ENV['APP_URL'], '/');
} else {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $baseUrl = $protocol . "://" . $host . $scriptDir;
}

// Initialize Router
$router = new Router($baseUrl);

// Define Routes
$router->add('', [$router, 'handleHome']);
$router->add('search', [$router, 'handleSearch']);
$router->add('categories', [$router, 'handleListCategories']);
$router->add('platforms', [$router, 'handleListPlatforms']);
$router->add('errors/([a-zA-Z0-9-]+)', [$router, 'handleError']);
$router->add('categories/([a-zA-Z0-9-]+)', [$router, 'handleCategory']);
$router->add('platforms/([a-zA-Z0-9-]+)', [$router, 'handlePlatform']);

// Sitemap Generation Trigger (Protected or Cron)
// In a real scenario, you might want to protect this route or run it via CLI only.
// For this MVP, accessing /generate-sitemap will trigger the generation.
$router->add('generate-sitemap', function() use ($baseUrl) {
    $generator = new SitemapGenerator($baseUrl, __DIR__ . '/data', __DIR__);
    $generator->generate();
    echo "Sitemaps generated successfully.";
});

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI']);
