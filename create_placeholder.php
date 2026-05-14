<?php
// Create placeholder images for products
$products = [
    'iphone15.jpg' => 'iPhone 15 Pro',
    'samsung_s24.jpg' => 'Samsung Galaxy S24', 
    'macbook.jpg' => 'MacBook Air M3',
    'sony_wh.jpg' => 'Sony WH-1000XM5',
    'white_shirt.jpg' => 'Classic White Shirt',
    'slim_jeans.jpg' => 'Slim Fit Jeans',
    'running_sneakers.jpg' => 'Running Sneakers',
    'coffee_beans.jpg' => 'Organic Coffee Beans',
    'olive_oil.jpg' => 'Premium Olive Oil',
    'smart_bulb.jpg' => 'Smart LED Bulb Set',
    'air_purifier.jpg' => 'Air Purifier',
    'yoga_mat.jpg' => 'Yoga Mat Pro'
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

$index = 0;
foreach ($products as $filename => $name) {
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
    
    // Add text
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $fontSize = 20;
    $angle = 0;
    
    // Split text into multiple lines if needed
    $words = explode(' ', $name);
    $lines = [];
    $currentLine = '';
    
    foreach ($words as $word) {
        $testLine = $currentLine ? $currentLine . ' ' . $word : $word;
        $bbox = imagettfbbox($fontSize, $angle, 'arial.ttf', $testLine);
        if ($bbox[2] - $bbox[0] < $width - 40) {
            $currentLine = $testLine;
        } else {
            if ($currentLine) {
                $lines[] = $currentLine;
            }
            $currentLine = $word;
        }
    }
    if ($currentLine) {
        $lines[] = $currentLine;
    }
    
    // Draw text lines
    $lineHeight = 30;
    $y = ($height - (count($lines) - 1) * $lineHeight) / 2;
    
    foreach ($lines as $line) {
        $bbox = imagettfbbox($fontSize, $angle, 'arial.ttf', $line);
        $x = ($width - ($bbox[2] - $bbox[0])) / 2;
        imagettftext($image, $fontSize, $angle, $x, $y, $textColor, 'arial.ttf', $line);
        $y += $lineHeight;
    }
    
    // Save image
    $filepath = "public/storage/products/$filename";
    imagejpeg($image, $filepath, 90);
    imagedestroy($image);
    
    echo "Created: $filepath\n";
    $index++;
}

echo "All placeholder images created!\n";
