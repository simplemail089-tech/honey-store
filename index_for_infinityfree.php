<?php

/**
 * Laravel - InfinityFree Modified Index
 * 
 * This file is modified to work on shared hosting without SSH access.
 * It should be placed in htdocs root directory.
 * 
 * IMPORTANT: Delete the original 'public' folder after copying its contents to htdocs root.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request
(require_once __DIR__.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
