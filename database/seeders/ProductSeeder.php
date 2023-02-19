<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a new user
        $userCount = User::count();

        if ($userCount === 0) {
            $user = User::factory()->create();
        } else {
            $user = User::inRandomOrder()->first();
        }

        // Create a new product
        $product = Product::create([
            'name' => 'Sample Product',
            'price' => 9.99,
            'status' => 'available',
            'product_type' => 'item',
            'user_id' => $user->id,
        ]);
    }
}
