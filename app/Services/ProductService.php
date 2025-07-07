<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ProductRepositoryInterface;

class ProductService implements \App\Contracts\ProductServiceInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->all();
    }

    public function paginateWithRelations($perPage, array $relations = [])
    {
        return $this->productRepository->paginateWithRelations($perPage, $relations);
    }

    public function filter(array $criteria, $sort, $perPage)
    {
        return $this->productRepository->filter($criteria, $sort, $perPage);
    }

    public function find($id)
    {
        return $this->productRepository->find($id);
    }

    public function create(array $data, ?\Illuminate\Http\UploadedFile $image): Product
    {
        if ($image) {
            $data['image_path'] = $image->store('products', 'public');
        }
        return $this->productRepository->create($data);
    }

    public function updateStock(Product $product, int $stock): Product
    {
        if ($stock < 0) {
            throw new \InvalidArgumentException('Stock cannot be negative');
        }
        return $this->productRepository->updateStock($product, $stock);
    }
}