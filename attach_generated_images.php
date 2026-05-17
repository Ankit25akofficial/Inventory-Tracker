<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

$artifactDir = 'C:\Users\asus\.gemini\antigravity\brain\3fd0fd3a-b760-4e80-9262-4e9f3eb96194';
$files = glob($artifactDir . '/*.png');

$imageMap = [
    'Wireless Keyboard' => 'wireless_keyboard',
    'Logitech Mouse' => 'logitech_mouse',
    'Dell 24" Monitor' => 'dell_monitor',
    'HDMI Cable 6ft' => 'hdmi_cable',
    'Ergonomic Chair' => 'ergonomic_chair',
    'Office Desk' => 'office_desk',
    'Cisco Router' => 'cisco_router',
    'Cat6 Cable Box' => 'cat6_cable',
    'USB-C Hub' => 'usb_hub',
    'Webcam 1080p' => 'webcam',
    'Mechanical Keyboard' => 'mechanical_keyboard',
    'Gaming Mouse' => 'gaming_mouse',
    'Ultrawide Monitor' => 'ultrawide_monitor',
    'DisplayPort Cable' => 'displayport_cable',
    'Standing Desk' => 'standing_desk',
    'Mesh Office Chair' => 'mesh_chair',
    'Wi-Fi Extender' => 'wifi_extender'
];

foreach ($imageMap as $productName => $prefix) {
    // Find the file that matches the prefix
    $matchedFile = null;
    foreach ($files as $file) {
        if (strpos(basename($file), $prefix) === 0) {
            $matchedFile = $file;
            break;
        }
    }
    
    if ($matchedFile) {
        $product = Product::where('name', $productName)->first();
        if ($product) {
            $filename = 'products/' . basename($matchedFile);
            Storage::disk('public')->put($filename, file_get_contents($matchedFile));
            $product->image_path = $filename;
            $product->save();
            echo "Attached to $productName\n";
        }
    }
}
