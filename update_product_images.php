<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Map product names to image files
$productImages = [
    'iPhone 15 Pro' => 'products/iphone15.jpg',
    'Samsung Galaxy S24' => 'products/samsung_s24.jpg',
    'MacBook Air M3' => 'products/macbook.jpg',
    'Sony WH-1000XM5' => 'products/sony_wh.jpg',
    'Classic White Shirt' => 'products/white_shirt.jpg',
    'Slim Fit Jeans' => 'products/slim_jeans.jpg',
    'Running Sneakers' => 'products/running_sneakers.jpg',
    'Organic Coffee Beans' => 'products/coffee_beans.jpg',
    'Premium Olive Oil' => 'products/olive_oil.jpg',
    'Smart LED Bulb Set' => 'products/smart_bulb.jpg',
    'Air Purifier' => 'products/air_purifier.jpg',
    'Yoga Mat Pro' => 'products/yoga_mat.jpg'
];

$products = App\Models\Product::all();
$updated = 0;

foreach ($products as $product) {
    if (isset($productImages[$product->name])) {
        $product->image = $productImages[$product->name];
        $product->save();
        echo "Updated: {$product->name} -> {$product->image}\n";
        $updated++;
    }
}

echo "\nUpdated {$updated} products with images!\n";
