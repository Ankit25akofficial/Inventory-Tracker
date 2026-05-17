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

foreach ($products as $product) {
    echo "Downloading realistic image for: {$product->name}... ";
    
    // We construct a highly descriptive prompt for the AI image generator
    $prompt = "professional sleek product photography of {$product->name}, top-down studio lighting, simple clean solid black background, highly detailed 8k";
    $url = "https://image.pollinations.ai/prompt/" . urlencode($prompt) . "?width=600&height=600&nologo=true";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // Timeout extended because generating might take 5-10 seconds
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
}

echo "\nAll done!\n";
