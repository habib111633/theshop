<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $existingCategory = Category::inRandomOrder()->first(); // Get a random existing category

        if ($existingCategory) {
            return [
                'name' => $existingCategory->name, // Use the existing category's name
            ];
        }

        return [
            'name' => fake()->word(), // Fallback to fake data if no categories exist
        ];
    }
}