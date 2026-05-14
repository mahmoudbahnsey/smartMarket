<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Product count: " . App\Models\Product::count() . PHP_EOL;
$products = App\Models\Product::take(5)->get(['id', 'name', 'image']);
foreach($products as $p) {
    echo "ID: {$p->id}, Name: {$p->name}, Image: " . ($p->image ?? 'null') . PHP_EOL;
}
