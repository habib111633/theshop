<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProduct(array $data, ?UploadedFile $image): Product
    {
        // dd($data, $image);
        $product = Product::create($data);
        if ($image) {
            $this->attachImage($product, $image);
        }

        return $product;
    }

    public function updateProduct(Product $product, array $data, ?UploadedFile $image): Product
    {
        $product->update($data);

        if ($image) {
            $product->media()->delete();
            $this->attachImage($product, $image);
        }

        return $product;
    }

    protected function attachImage(Product $product, UploadedFile $image): void
    {
        $path = $image->store('products', 'public');

        $product->media()->create([
            'path'           => $path, // THIS MUST BE INCLUDED
            'imageable_id'   => $product->id,
            'imageable_type' => get_class($product),
            // created_at and updated_at are automatically handled
        ]);
    }

    // app/Services/ProductService.php

    public function deleteProduct(Product $product): bool
    {
        try {
            // Delete associated media files first
            $this->deleteProductMedia($product);

            // Then delete the product
            return $product->delete();

        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            throw new \RuntimeException('Failed to delete product: ' . $e->getMessage());
        }
    }

    protected function deleteProductMedia(Product $product): void
    {
        foreach ($product->media as $media) {
            // Delete physical file

            if (! Storage::disk('public')->exists($media->path)) {
                Log::warning('Media file does not exist: ' . $media->path);
                continue;
            }
            Storage::disk('public')->delete($media->path);
            // Delete media record
            $media->delete();
        }
    }
}