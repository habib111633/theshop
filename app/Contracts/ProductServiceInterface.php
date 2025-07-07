<?php
namespace App\Contracts;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

interface ProductServiceInterface {
    
    public function create(array $data, ?UploadedFile $image): Product;
  }