<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function carts()
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        return view('client.carts', [
            'shop' => $shop,
            'title' => 'Carts',
        ]);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $id = (int) $validated['product_id'];
        $requestedQty = (int) $validated['quantity'];
        $product = Product::findOrFail($id);

        if ($product->stock <= 0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product is out of stock.',
                'cartCount' => count((array) session('cart')),
                'code' => 202,
            ], 202);
        }

        $cart = (array) session()->get('cart', []);

        if (isset($cart[$id])) {
            $quantityUpdate = $cart[$id]['quantity'] + $requestedQty;

            if ($quantityUpdate > $product->stock) {
                return response()->json([
                    'status' => 'failed',
                    'cartCount' => count($cart),
                    'code' => 202,
                ], 202);
            }

            $cart[$id]['quantity'] = $quantityUpdate;
            $cart[$id]['price'] = $product->price;
            $cart[$id]['product_stock'] = $product->stock;
            session()->put('cart', $cart);

            return response()->json([
                'status' => 'success',
                'cartCount' => count($cart),
                'code' => 201,
            ], 201);
        }

        if ($requestedQty > $product->stock) {
            return response()->json([
                'status' => 'failed',
                'cartCount' => count($cart),
                'code' => 202,
            ], 202);
        }

        $cart[$id] = [
            'product_id' => $id,
            'title' => $product->title,
            'quantity' => $requestedQty,
            'price' => $product->price,
            'product_stock' => $product->stock,
        ];

        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'cartCount' => count($cart),
            'code' => 200,
        ], 200);
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $id = (int) $validated['product_id'];
        $quantity = (int) $validated['quantity'];
        $cart = (array) session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(['message' => 'Item not in cart'], 404);
        }

        $product = Product::find($id);
        if (!$product || $quantity > $product->stock) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }

        $cart[$id]['quantity'] = $quantity;
        $cart[$id]['price'] = $product->price;
        $cart[$id]['product_stock'] = $product->stock;
        session()->put('cart', $cart);

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return response()->json([
            'message' => 'Success',
            'total' => $total,
            'cartCount' => count($cart),
        ]);
    }

    public function deleteCart(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $id = (int) $validated['id'];
        $cart = (array) session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return response()->json([
            'message' => 'Success',
            'total' => $total,
            'cartCount' => count($cart),
        ]);
    }
}
