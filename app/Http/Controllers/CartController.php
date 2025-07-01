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
        $quantity = max(1, (int)$request->input('quantity', 1));
        $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        // Remove from cart if out of stock
        if ($product->stock <= 0) {
            unset($cart[$product->id]);
            session(['cart' => $cart]);
            return response()->json([
                'error' => 'This product is out of stock and has been removed from your cart.',
                'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
                'count' => array_sum(array_column($cart, 'quantity')),
            ], 422);
        }

        // If requested quantity exceeds stock, set to max available
        if ($newQty > $product->stock) {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->media->first() ? $product->media->first()->path : null,
                'quantity' => $product->stock,
            ];
            session(['cart' => $cart]);
            return response()->json([
                'error' => 'Not enough stock. Cart updated to maximum available.',
                'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
                'count' => array_sum(array_column($cart, 'quantity')),
            ], 422);
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $newQty;
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
        $updated = false;
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock <= 0) {
                unset($cart[$productId]);
                $updated = true;
            } elseif ($item['quantity'] > $product->stock) {
                $cart[$productId]['quantity'] = $product->stock;
                $updated = true;
            }
        }
        if ($updated) {
            session(['cart' => $cart]);
        }
        return view('partials.cart-preview', ['cart' => $cart]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');
        $quantity = max(1, (int)$request->input('quantity', 1));

        $product = Product::findOrFail($productId);
        if ($product->stock <= 0) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
            return response()->json([
                'error' => 'This product is out of stock and has been removed from your cart.',
                'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
                'count' => array_sum(array_column($cart, 'quantity')),
            ], 422);
        }
        if ($quantity > $product->stock) {
            $cart[$productId]['quantity'] = $product->stock;
            session(['cart' => $cart]);
            return response()->json([
                'error' => 'Not enough stock. Cart updated to maximum available.',
                'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
                'count' => array_sum(array_column($cart, 'quantity')),
            ], 422);
        }
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session(['cart' => $cart]);
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = round($subtotal * 0.17, 2); // 17% tax
        $shipping = 0.00;
        $total = $subtotal + $tax + $shipping;

        return response()->json([
            'success' => true,
            'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
            'count' => array_sum(array_column($cart, 'quantity')),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
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
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = round($subtotal * 0.17, 2); // 17% tax
        $shipping = 0.00;
        $total = $subtotal + $tax + $shipping;
        
        return response()->json([
            'success' => true,
            'preview' => view('partials.cart-preview', ['cart' => $cart])->render(),
            'count' => array_sum(array_column($cart, 'quantity')),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }
}
