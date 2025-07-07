<?php
namespace App\Contracts;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

interface ProductServiceInterface {
    
    public function create(array $data, ?UploadedFile $image): Product;
    public function updateStock(Product $product, int $stock): Product;
    public function getAllProducts();
    public function paginateWithRelations($perPage, array $relations = []);
    public function filter(array $criteria, $sort, $perPage);
    public function find($id);
  }