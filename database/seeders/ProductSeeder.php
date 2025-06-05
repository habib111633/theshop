<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some users first if they don't exist
        if (\App\Models\User::count() === 0) {
            \App\Models\User::factory(10)->create();
        }
// Get existing user IDs
        $userIds = \App\Models\User::pluck('id')->toArray();
// Create products with existing user IDs
        Product::factory(167)->create([
            'user_id' => fn() => $userIds[array_rand($userIds)],
        ]);

    }
}