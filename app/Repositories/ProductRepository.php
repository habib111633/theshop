<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
         return Product::all();
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function findWithRelations($id, array $relations = [])
    {
        return Product::with($relations)->findOrFail($id);
    }

    public function paginateWithRelations($perPage, array $relations = [])
    {
        return Product::with($relations)->paginate($perPage);
    }

    public function filter(array $criteria, $sort, $perPage)
    {
        $query = Product::query();

        if (!empty($criteria['categories'])) {
            $query->whereIn('category_id', $criteria['categories']);
        }

        switch ($sort) {
            case 'az':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->with('media', 'category')->paginate($perPage);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }

    public function updateStock(Product $product, int $stock)
    {
        $product->stock = $stock;
        $product->save();
        return $product;
    }
}
