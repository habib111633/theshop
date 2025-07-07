<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function findWithRelations($id, array $relations = []);
    public function paginateWithRelations($perPage, array $relations = []);
    public function filter(array $criteria, $sort, $perPage);
    public function create(array $data);
    public function update(Product $product, array $data);
    public function delete(Product $product);
    public function updateStock(Product $product, int $stock);
} 