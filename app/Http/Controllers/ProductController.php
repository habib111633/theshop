<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;  
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Contracts\ProductServiceInterface;


class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
        $this->middleware('can:view products')->only(['index', 'show']);
        $this->middleware('can:create products')->only(['create', 'store']);
        $this->middleware('can:update products')->only(['edit', 'update']);
        $this->middleware('can:delete products')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $products = $this->productService->getAllProducts();
        return view('products.index', compact('products'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
$categories = Category::all();

return view('products.create', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
$product = $this->productService->createProduct(
    $request->validated() + ['user_id' => auth()->id()],
    $request->file('image')
);

return redirect()->route('admin.products.index')
    ->with('success', 'Product created!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
return view('products.show', compact('product'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
$categories = Category::all();
return view('products.create', compact('product', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
try {
    $this->productService->updateProduct($product, $request->validated(), $request->file('image'));
    return redirect()->route('admin.products.index')
        ->with('success', 'Product updated successfully!');
} catch (\Exception $e) {
    return redirect()->route('products.index')
        ->with('error', 'Failed to update product: ' . $e->getMessage());
}

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        try{
            $this->productService->deleteProduct($product);
            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function shop()
{
$products = $this->productService->paginateWithRelations(9, ['media', 'category']);
$products->withPath(route('shop.ajax'));
$categories = Category::all();
return view('shop', compact('products','categories'));
}
public function ajaxFilter(Request $request)
{
    $criteria = [];
    if ($request->has('categories')) {
        $criteria['categories'] = $request->input('categories', []);
    }
    $sort = $request->input('sort', 'latest');
    $products = $this->productService->filter($criteria, $sort, 9);
    $products->appends($request->except('page'));
    return view('partials.products-grid', compact('products'))->render();
}
public function publicDetail(Product $product)
{
    return view('single-product-page', compact('product'));
}

public function updateStock(Request $request, Product $product)
{
    $request->validate([
        'stock' => 'required|integer|min:0',
    ]);
    $this->productService->updateStock($product, $request->stock);
    return response()->json([
        'success' => true,
        'stock' => $product->stock,
        'message' => 'Stock updated successfully!',
    ]);
}

public function availableStock($id)
{
    $product = $this->productService->find($id);
    $cart = session()->get('cart', []);
    $cartQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
    $available = $product->stock - $cartQty;
    return response()->json(['available_stock' => max(0, $available)]);
}
}
