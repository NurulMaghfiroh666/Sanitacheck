<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if the request is for the landing page
if ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php') {
    header('Location: beranda.php');
    exit;
}

// Load Laravel for all other routes
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
