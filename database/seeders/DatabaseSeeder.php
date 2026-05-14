<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Cart;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ────────────────────────────────────────────────────────────
        User::create([
            'name'      => 'Admin User',
            'email'     => 'admin@smartmarket.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // ─── Branch Managers ──────────────────────────────────────────────────
        $manager1 = User::create([
            'name'      => 'Cairo Manager',
            'email'     => 'cairo@smartmarket.com',
            'password'  => Hash::make('manager123'),
            'role'      => 'branch_manager',
            'phone'     => '+20 2 1234 5678',
            'is_active' => true,
        ]);

        $manager2 = User::create([
            'name'      => 'Alex Manager',
            'email'     => 'alex@smartmarket.com',
            'password'  => Hash::make('manager123'),
            'role'      => 'branch_manager',
            'phone'     => '+20 3 9876 5432',
            'is_active' => true,
        ]);

        // ─── Customers ────────────────────────────────────────────────────────
        $customers = [
            ['name' => 'Ahmed Hassan', 'email' => 'ahmed@example.com'],
            ['name' => 'Sara Mohamed', 'email' => 'sara@example.com'],
            ['name' => 'Omar Khaled',  'email' => 'omar@example.com'],
        ];
        foreach ($customers as $c) {
            $user = User::create([
                'name'      => $c['name'],
                'email'     => $c['email'],
                'password'  => Hash::make('customer123'),
                'role'      => 'customer',
                'is_active' => true,
            ]);
            // One-to-One: each customer has exactly one cart
            Cart::create(['user_id' => $user->id]);
        }

        // ─── Categories ───────────────────────────────────────────────────────
        $cats = [];
        $catData = [
            ['name' => 'Electronics',   'slug' => 'electronics'],
            ['name' => 'Clothing',      'slug' => 'clothing'],
            ['name' => 'Food & Drinks', 'slug' => 'food-drinks'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden'],
            ['name' => 'Sports',        'slug' => 'sports'],
            ['name' => 'Books',         'slug' => 'books'],
        ];
        foreach ($catData as $c) {
            $cats[] = Category::create(array_merge($c, ['is_active' => true]));
        }

        // ─── Branches (One-to-One with manager) ───────────────────────────────
        $branch1 = Branch::create([
            'name'       => 'Cairo Branch',
            'location'   => 'Nasr City, Cairo',
            'city'       => 'Cairo',
            'phone'      => '+20 2 2345 6789',
            'email'      => 'cairo@smartmarket.com',
            'address'    => '15 Abbas El-Akkad St, Nasr City',
            'is_active'  => true,
            'manager_id' => $manager1->id,
        ]);

        $branch2 = Branch::create([
            'name'       => 'Alexandria Branch',
            'location'   => 'Smouha, Alexandria',
            'city'       => 'Alexandria',
            'phone'      => '+20 3 4567 8901',
            'email'      => 'alex@smartmarket.com',
            'address'    => '22 Victor Emmanuel St, Smouha',
            'is_active'  => true,
            'manager_id' => $manager2->id,
        ]);

        $branch3 = Branch::create([
            'name'      => 'Giza Branch',
            'location'  => 'Dokki, Giza',
            'city'      => 'Giza',
            'phone'     => '+20 2 3456 7890',
            'is_active' => true,
        ]);

        // ─── Products (with images) ───────────────────────────────────────────
        $products = [
            ['name'=>'iPhone 15 Pro',       'price'=>1299.99,'sale'=>null,   'cat'=>0,'featured'=>true, 'image'=>'products/iphone15.jpg',    'desc'=>'The most powerful iPhone ever with A17 Pro chip.'],
            ['name'=>'Samsung Galaxy S24',  'price'=>999.99, 'sale'=>899.99, 'cat'=>0,'featured'=>true, 'image'=>'products/samsung_s24.jpg', 'desc'=>'Galaxy AI is here. Power of AI in your pocket.'],
            ['name'=>'MacBook Air M3',      'price'=>1499.99,'sale'=>null,   'cat'=>0,'featured'=>false,'image'=>'products/macbook.jpg',     'desc'=>'Supercharged by M3 chip with all-day battery.'],
            ['name'=>'Sony WH-1000XM5',     'price'=>349.99, 'sale'=>299.99, 'cat'=>0,'featured'=>false,'image'=>'products/sony_wh.jpg',     'desc'=>'Industry-leading noise canceling headphones.'],
            ['name'=>'Classic White Shirt', 'price'=>49.99,  'sale'=>null,   'cat'=>1,'featured'=>false,'image'=>'products/white_shirt.jpg', 'desc'=>'Premium cotton classic white shirt.'],
            ['name'=>'Slim Fit Jeans',      'price'=>79.99,  'sale'=>59.99,  'cat'=>1,'featured'=>true, 'image'=>'products/jeans.jpg',       'desc'=>'Modern slim fit jeans from stretch denim.'],
            ['name'=>'Running Sneakers',    'price'=>129.99, 'sale'=>null,   'cat'=>1,'featured'=>false,'image'=>'products/sneakers.jpg',    'desc'=>'Lightweight sneakers with advanced cushioning.'],
            ['name'=>'Organic Coffee Beans','price'=>24.99,  'sale'=>null,   'cat'=>2,'featured'=>false,'image'=>'products/coffee.jpg',      'desc'=>'100% organic Arabica coffee beans.'],
            ['name'=>'Premium Olive Oil',   'price'=>19.99,  'sale'=>14.99,  'cat'=>2,'featured'=>false,'image'=>'products/olive_oil.jpg',   'desc'=>'Extra virgin olive oil from Mediterranean.'],
            ['name'=>'Smart LED Bulb Set',  'price'=>39.99,  'sale'=>null,   'cat'=>3,'featured'=>true, 'image'=>'products/led_bulb.jpg',    'desc'=>'Smart LED bulbs with 16 million colors.'],
            ['name'=>'Air Purifier',        'price'=>199.99, 'sale'=>169.99, 'cat'=>3,'featured'=>false,'image'=>'products/air_purifier.jpg','desc'=>'HEPA air purifier removes 99.97% of particles.'],
            ['name'=>'Yoga Mat Pro',        'price'=>59.99,  'sale'=>null,   'cat'=>4,'featured'=>false,'image'=>'products/yoga_mat.jpg',    'desc'=>'Non-slip professional yoga mat.'],
            ['name'=>'Dumbbell Set 20kg',   'price'=>89.99,  'sale'=>74.99,  'cat'=>4,'featured'=>false,'image'=>'products/dumbbell.jpg',    'desc'=>'Adjustable dumbbell set for home workouts.'],
            ['name'=>'Clean Code',          'price'=>34.99,  'sale'=>null,   'cat'=>5,'featured'=>false,'image'=>'products/clean_code.jpg',  'desc'=>'A handbook of agile software craftsmanship.'],
            ['name'=>'Laravel Up & Running','price'=>44.99,  'sale'=>39.99,  'cat'=>5,'featured'=>false,'image'=>'products/laravel.jpg',     'desc'=>'Complete guide to building apps with Laravel.'],
        ];

        $productModels = [];
        foreach ($products as $p) {
            $productModels[] = Product::create([
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']) . '-' . uniqid(),
                'description' => $p['desc'],
                'price'       => $p['price'],
                'sale_price'  => $p['sale'],
                'category_id' => $cats[$p['cat']]->id,
                'is_featured' => $p['featured'],
                'is_active'   => true,
                'image'       => $p['image'],
            ]);
        }

        // ─── Inventory ────────────────────────────────────────────────────────
        $branches  = [$branch1, $branch2, $branch3];
        $stockData = [
            [45, 30, 20], [60, 40, 25], [20, 15, 10], [80, 60, 35],
            [100,80, 50], [3,  70, 40], [55, 45, 30], [200,150,90],
            [0,  80, 60], [90, 70, 45], [25, 20, 12], [40, 35, 20],
            [30, 25, 15], [150,100,75], [120,90, 60],
        ];

        foreach ($productModels as $idx => $product) {
            foreach ($branches as $bIdx => $branch) {
                Inventory::create([
                    'product_id'      => $product->id,
                    'branch_id'       => $branch->id,
                    'quantity'        => $stockData[$idx][$bIdx] ?? 50,
                    'low_stock_alert' => 5,
                ]);
            }
        }

        $this->command->info('');
        $this->command->info('✅ Seeded successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('  Admin:    admin@smartmarket.com   / admin123');
        $this->command->info('  Manager:  cairo@smartmarket.com   / manager123');
        $this->command->info('  Customer: ahmed@example.com       / customer123');
    }
}
