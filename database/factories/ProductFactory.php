<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 10, 1000),
            'stock'       => fake()->numberBetween(0, 100),
            'category_id' => Category::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Create a Media instance associated with the product
            $media = Media::factory()->create([
                'imageable_id'   => $product->id,
                'imageable_type' => Product::class,
            ]);

            // Log the associated media for debugging
            Log::info('Media associated with product', [
                'product_id' => $product->id,
                'media_id'   => $media->id,
                'path'       => $media->path,
            ]);
        });
    }
}