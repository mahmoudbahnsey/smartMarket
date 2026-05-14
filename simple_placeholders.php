<?php
// Create simple colored placeholder images
$products = [
    'iphone15.jpg',
    'samsung_s24.jpg', 
    'macbook.jpg',
    'sony_wh.jpg',
    'white_shirt.jpg',
    'slim_jeans.jpg',
    'running_sneakers.jpg',
    'coffee_beans.jpg',
    'olive_oil.jpg',
    'smart_bulb.jpg',
    'air_purifier.jpg',
    'yoga_mat.jpg'
];

$colors = [
    '#007AFF', // Blue
    '#1D1D1F', // Dark Gray
    '#A8DADC', // Light Blue
    '#457B9D', // Navy
    '#F1FAEE', // Off White
    '#E63946', // Red
    '#2A9D8F', // Teal
    '#264653', // Dark Green
    '#F4A261', // Orange
    '#E76F51', // Coral
    '#FFB700', // Yellow
    '#06FFA5'  // Bright Green
];

foreach ($products as $index => $filename) {
    $width = 400;
    $height = 400;
    
    $image = imagecreatetruecolor($width, $height);
    
    // Parse hex color
    $hex = $colors[$index % count($colors)];
    $r = hexdec(substr($hex, 1, 2));
    $g = hexdec(substr($hex, 3, 2));
    $b = hexdec(substr($hex, 5, 2));
    
    // Fill background
    $bgColor = imagecolorallocate($image, $r, $g, $b);
    imagefill($image, 0, 0, $bgColor);
    
    // Add a simple pattern
    $patternColor = imagecolorallocate($image, $r * 0.8, $g * 0.8, $b * 0.8);
    for ($i = 0; $i < 10; $i++) {
        $x1 = rand(0, $width);
        $y1 = rand(0, $height);
        $x2 = $x1 + rand(20, 80);
        $y2 = $y1 + rand(20, 80);
        imagefilledellipse($image, $x1, $y1, $x2 - $x1, $y2 - $y1, $patternColor);
    }
    
    // Save image
    $filepath = "public/storage/products/$filename";
    imagejpeg($image, $filepath, 90);
    imagedestroy($image);
    
    echo "Created: $filepath\n";
}

echo "All placeholder images created!\n";
