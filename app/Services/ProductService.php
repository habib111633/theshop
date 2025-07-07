<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function create(): Product
    {
        return Product::create([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'price' => 100,
        ]);
    }
}