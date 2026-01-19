<?php

// Simple test to check if articles exist and can be fetched
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Now test the Article model
$articles = \App\Models\Article::all();

echo "Total Articles: " . count($articles) . "\n";
echo json_encode($articles, JSON_PRETTY_PRINT) . "\n";

$kernel->terminate($request, $response);
