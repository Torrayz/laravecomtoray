<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index()
    {
        if (!Shop::exists()) {
            return redirect()->route('register');
        }

        return view('client.index', [
            'shop' => Shop::first(),
            'product' => Product::with(['category', 'productImage'])->orderByDesc('id')->take(8)->get(),
            'category' => Category::orderByDesc('id')->take(4)->get(),
            'title' => 'Home',
        ]);
    }

    public function products()
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        return view('client.products', [
            'shop' => $shop,
            'product' => Product::with(['category', 'productImage'])->orderByDesc('id')->paginate(16),
            'category' => Category::orderByDesc('id')->get(),
            'title' => 'Products',
        ]);
    }

    public function searchProduct(Request $request)
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        $validator = Validator::make($request->all(), [
            'product' => 'required|string|max:120',
        ]);

        if ($validator->fails()) {
            return redirect()->route('clientHome')->withErrors($validator)->withInput();
        }

        $search = str_replace(' ', '-', strtolower(trim($request->product)));

        return view('client.productSearch', [
            'title' => 'Result',
            'shop' => $shop,
            'product' => Product::with(['category', 'productImage'])
                ->where('title', 'LIKE', '%' . $search . '%')
                ->orderByDesc('id')
                ->paginate(20),
            'search' => $request->product,
        ]);
    }

    public function category()
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        return view('client.category', [
            'shop' => $shop,
            'category' => Category::orderByDesc('id')->paginate(12),
            'title' => 'Categories',
        ]);
    }

    public function categoryProducts($category)
    {
        $shop = Shop::firstOrFail();
        $categoryModel = Category::with(['product.category', 'product.productImage'])
            ->where('name', $category)
            ->firstOrFail();

        return view('client.categoryProducts', [
            'shop' => $shop,
            'category' => $categoryModel,
            'title' => 'Category - ' . str_replace('-', ' ', ucwords($categoryModel->name)),
        ]);
    }

    public function productDetail($product)
    {
        $shop = Shop::firstOrFail();
        $productModel = Product::with(['category.product.productImage', 'productImage'])
            ->where('title', $product)
            ->firstOrFail();

        if ($productModel->category && $productModel->category->product->count() > 1) {
            $recomendationProducts = $productModel->category->product
                ->where('id', '!=', $productModel->id)
                ->take(8);
        } else {
            $recomendationProducts = Product::with(['category', 'productImage'])
                ->where('id', '!=', $productModel->id)
                ->orderByDesc('id')
                ->take(8)
                ->get();
        }

        return view('client.productDetail', [
            'shop' => $shop,
            'product' => $productModel,
            'recomendationProducts' => $recomendationProducts,
            'title' => str_replace('-', ' ', ucwords($productModel->title)),
        ]);
    }

    public function checkout()
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        return view('client.checkout', [
            'shop' => $shop,
            'title' => 'Checkout',
        ]);
    }

    public function checkoutSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('clientCheckout')->withErrors($validator)->withInput();
        }

        $cart = (array) session('cart', []);
        if (empty($cart)) {
            return redirect()->route('clientCarts')->with('failed', 'Your cart is empty.');
        }

        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('clientHome')->with('failed', 'Store is not configured yet.');
        }

        $orderCode = strtoupper(Str::random(4)) . '-' . date('Ymd');

        try {
            DB::transaction(function () use ($request, $cart, $shop, $orderCode) {
                $productIds = array_keys($cart);
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $total = 0;
                $orderDetails = [];

                foreach ($cart as $id => $details) {
                    $product = $products->get((int) $id);
                    $quantity = max(1, (int) ($details['quantity'] ?? 1));

                    if (!$product || $product->stock < $quantity) {
                        throw new \RuntimeException('Insufficient stock for an item in your cart.');
                    }

                    $linePrice = (int) $product->price;
                    $total += $linePrice * $quantity;

                    $orderDetails[] = [
                        'order_code' => $orderCode,
                        'title' => $product->title,
                        'price' => $linePrice,
                        'quantity' => $quantity,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Order::create([
                    'shop_id' => $shop->id,
                    'order_code' => $orderCode,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'note' => $request->note,
                    'total' => $total,
                    'status' => 0,
                ]);

                OrderDetail::insert($orderDetails);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('clientCarts')->with('failed', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()->route('clientOrderCode', $orderCode);
    }

    public function successOrder($order_code)
    {
        $shop = Shop::firstOrFail();

        return view('client.success-order', [
            'shop' => $shop,
            'order_code' => $order_code,
            'title' => 'Checkout',
        ]);
    }

    public function checkOrder()
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        return view('client.check-order', [
            'shop' => $shop,
            'title' => 'Check Order',
        ]);
    }

    public function checkOrderStatus(Request $request)
    {
        $shop = Shop::firstOrFail();
        $code = trim((string) $request->order_code);
        $order = Order::where('order_code', $code)->first();

        if ($order) {
            return view('client.check-order', [
                'shop' => $shop,
                'order' => $order,
                'orderDetail' => OrderDetail::where('order_code', $code)->get(),
                'title' => 'Check Order',
            ]);
        }

        return view('client.check-order', [
            'shop' => $shop,
            'notFound' => true,
            'searchedCode' => $code,
            'title' => 'Check Order',
        ]);
    }

    public function about()
    {
        $shop = Shop::first();
        if (!$shop) {
            return redirect()->route('register');
        }

        return view('client.about', [
            'shop' => $shop,
            'title' => 'About',
        ]);
    }
}
