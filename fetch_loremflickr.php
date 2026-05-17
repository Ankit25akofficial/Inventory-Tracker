<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$products = Product::all();
echo "Found " . $products->count() . " products.\n";

if (!Storage::disk('public')->exists('products')) {
    Storage::disk('public')->makeDirectory('products');
}

$keywordMapping = [
    'Wireless Keyboard' => 'keyboard,computer',
    'Logitech Mouse' => 'computer,mouse',
    'Dell 24" Monitor' => 'computer,monitor',
    'HDMI Cable 6ft' => 'hdmi,cable',
    'Ergonomic Chair' => 'office,chair',
    'Office Desk' => 'office,desk',
    'Cisco Router' => 'network,router',
    'Cat6 Cable Box' => 'ethernet,cable',
    'USB-C Hub' => 'usb,hub',
    'Webcam 1080p' => 'webcam',
    'Mechanical Keyboard' => 'mechanical,keyboard',
    'Gaming Mouse' => 'gaming,mouse',
    'Ultrawide Monitor' => 'ultrawide,monitor',
    'DisplayPort Cable' => 'displayport,cable',
    'Standing Desk' => 'standing,desk',
    'Mesh Office Chair' => 'office,chair',
    'Wi-Fi Extender' => 'wifi,router',
    'Network Switch' => 'network,switch',
    'Wireless Headset' => 'headset,headphones',
    'USB Flash Drive 64GB' => 'usb,flashdrive',
    'External HDD 2TB' => 'harddrive',
    'SSD 1TB' => 'ssd,drive',
    'Laptop Stand' => 'laptop,stand',
    'Mouse Pad' => 'mousepad',
    'Desk Lamp' => 'desk,lamp',
];

foreach ($products as $product) {
    echo "Downloading image for: {$product->name}... ";
    
    $keyword = $keywordMapping[$product->name] ?? 'gadget';
    $url = "https://loremflickr.com/600/600/" . urlencode($keyword);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $imageData && strlen($imageData) > 1000) {
        $filename = 'products/' . Str::slug($product->name) . '-' . uniqid() . '.jpg';
        
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        Storage::disk('public')->put($filename, $imageData);
        $product->image_path = $filename;
        $product->save();
        echo "Success!\n";
    } else {
        echo "Failed. HTTP: $httpCode\n";
    }
    
    // Slight delay to prevent rate limiting
    usleep(500000); 
}

echo "\nAll done!\n";
