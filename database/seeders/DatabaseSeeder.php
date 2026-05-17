<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\User;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate(
            ['email' => 'ankitkumar252508@gmail.com'],
            ['name' => 'Ankit Kumar', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $categories = [
            ['name' => 'Electronics', 'desc' => 'Gadgets and electronic devices'],
            ['name' => 'Hardware', 'desc' => 'Physical tools and computer hardware components'],
            ['name' => 'Office Supplies', 'desc' => 'Daily office consumables and stationery'],
            ['name' => 'Furniture', 'desc' => 'Office desks, chairs, and ergonomic furniture'],
            ['name' => 'Networking', 'desc' => 'Routers, switches, and network infrastructure'],
            ['name' => 'Software', 'desc' => 'Licenses, subscriptions, and digital software'],
            ['name' => 'Accessories', 'desc' => 'Computer peripherals and minor accessories']
        ];
        $catIds = [];
        foreach ($categories as $cat) {
            $catIds[] = Category::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['desc']]
            )->id;
            
            // Also update existing ones just in case
            Category::where('name', $cat['name'])->update(['description' => $cat['desc']]);
        }

        $suppliers = ['TechSource Inc.', 'Global Hardware Ltd', 'OfficeMax Direct', 'NetSys Solutions', 'Elite Furnishings'];
        $supIds = [];
        foreach ($suppliers as $sup) {
            $supIds[] = Supplier::firstOrCreate(['name' => $sup, 'email' => strtolower(str_replace(' ', '', $sup)).'@example.com', 'phone' => '1-800-'.rand(100,999).'-'.rand(1000,9999)])->id;
        }

        $products = [
            ['name' => 'Wireless Keyboard', 'sku' => 'KEY-001', 'price' => 45.00],
            ['name' => 'Logitech Mouse', 'sku' => 'MOU-002', 'price' => 25.00],
            ['name' => 'Dell 24" Monitor', 'sku' => 'MON-003', 'price' => 150.00],
            ['name' => 'HDMI Cable 6ft', 'sku' => 'CAB-004', 'price' => 10.00],
            ['name' => 'Ergonomic Chair', 'sku' => 'CHR-005', 'price' => 200.00],
            ['name' => 'Office Desk', 'sku' => 'DSK-006', 'price' => 300.00],
            ['name' => 'Cisco Router', 'sku' => 'ROU-007', 'price' => 120.00],
            ['name' => 'Cat6 Cable Box', 'sku' => 'CAB-008', 'price' => 80.00],
            ['name' => 'USB-C Hub', 'sku' => 'HUB-009', 'price' => 35.00],
            ['name' => 'Webcam 1080p', 'sku' => 'CAM-010', 'price' => 60.00],
            ['name' => 'Mechanical Keyboard', 'sku' => 'KEY-011', 'price' => 110.00],
            ['name' => 'Gaming Mouse', 'sku' => 'MOU-012', 'price' => 50.00],
            ['name' => 'Ultrawide Monitor', 'sku' => 'MON-013', 'price' => 350.00],
            ['name' => 'DisplayPort Cable', 'sku' => 'CAB-014', 'price' => 15.00],
            ['name' => 'Standing Desk', 'sku' => 'DSK-015', 'price' => 450.00],
            ['name' => 'Mesh Office Chair', 'sku' => 'CHR-016', 'price' => 180.00],
            ['name' => 'Wi-Fi Extender', 'sku' => 'ROU-017', 'price' => 45.00],
            ['name' => 'Network Switch', 'sku' => 'SWI-018', 'price' => 75.00],
            ['name' => 'Wireless Headset', 'sku' => 'AUD-019', 'price' => 90.00],
            ['name' => 'USB Flash Drive 64GB', 'sku' => 'STO-020', 'price' => 12.00],
            ['name' => 'External HDD 2TB', 'sku' => 'STO-021', 'price' => 65.00],
            ['name' => 'SSD 1TB', 'sku' => 'STO-022', 'price' => 85.00],
            ['name' => 'Laptop Stand', 'sku' => 'ACC-023', 'price' => 25.00],
            ['name' => 'Mouse Pad', 'sku' => 'ACC-024', 'price' => 8.00],
            ['name' => 'Desk Lamp', 'sku' => 'LIG-025', 'price' => 30.00]
        ];

        foreach ($products as $p) {
            $product = Product::firstOrCreate(
                ['sku' => $p['sku']],
                [
                    'name' => $p['name'],
                    'category_id' => $catIds[array_rand($catIds)],
                    'supplier_id' => $supIds[array_rand($supIds)],
                    'quantity' => rand(5, 100),
                    'min_stock_level' => rand(5, 20),
                    'purchase_price' => $p['price'] * 0.7,
                    'selling_price' => $p['price'],
                ]
            );

            // Generate 3-8 stock logs for each product over the last 30 days
            $numLogs = rand(3, 8);
            for ($i = 0; $i < $numLogs; $i++) {
                $daysAgo = rand(1, 30);
                $action = rand(0, 1) ? 'in' : 'out';
                StockLog::create([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'action' => $action,
                    'quantity' => rand(1, 15),
                    'remarks' => $action === 'in' ? 'Restock' : 'Dispatch to dept',
                    'created_at' => Carbon::now()->subDays($daysAgo)->subHours(rand(1,23))
                ]);
            }
        }
    }
}
