<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
{


    $product = Product::findOrFail($request->product_id);
    $cart = session()->get('cart', []);
    $quantity = max(1, (int)$request->input('quantity', 1)); // <-- Cast to int and ensure >= 1

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantity;
    } else {
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->media->first() ? $product->media->first()->path : null,
            'quantity' => $quantity,
        ];
    }

    session(['cart' => $cart]);

    logger()->info('Cart contents:', session('cart', []));

    return response()->json([
        'preview' => view('partials.cart-preview', ['cart' => session('cart', [])])->render(),
        'count' => array_sum(array_column(session('cart', []), 'quantity')),
    ]);
}

    public function preview()
    {
        $cart = session('cart', []);
        return view('partials.cart-preview', ['cart' => $cart]);
    }

    public function update(Request $request)
{
    $cart = session()->get('cart', []);
    $productId = $request->input('product_id');
    $quantity = max(1, (int)$request->input('quantity', 1));

    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
        session(['cart' => $cart]);
    }

    // Optionally return updated cart HTML for AJAX
    return response()->json([
        'success' => true,
        'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
        'count' => array_sum(array_column($cart, 'quantity')),
    ]);
}

public function remove(Request $request)
{
    $cart = session()->get('cart', []);
    $productId = $request->input('product_id');
    if (isset($cart[$productId])) {
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }
    return response()->json([
        'success' => true,
        'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
        'count' => array_sum(array_column($cart, 'quantity')),
    ]);
}

}
