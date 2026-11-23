<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.order.index', [
            'orders' => Order::orderByDesc('id')->get(),
            'title' => 'Orders',
        ]);
    }

    public function detail($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();

        return view('admin.order.detail', [
            'order' => $order,
            'orderDetail' => OrderDetail::where('order_code', $order_code)->get(),
            'title' => 'Order Detail - ' . $order_code,
        ]);
    }

    public function updateStatus(Request $request, $order_code)
    {
        $validated = $request->validate([
            'status' => 'required|integer|between:0,5',
        ]);

        $newStatus = (int) $validated['status'];

        try {
            DB::transaction(function () use ($order_code, $newStatus) {
                $order = Order::where('order_code', $order_code)->lockForUpdate()->firstOrFail();
                $previousStatus = (int) $order->status;
                $orderDetails = OrderDetail::where('order_code', $order_code)->get();

                if ($previousStatus !== 5 && $newStatus === 5) {
                    foreach ($orderDetails as $item) {
                        $product = Product::where('title', $item->title)->lockForUpdate()->first();
                        if ($product) {
                            $qty = (int) $item->quantity;
                            if ($product->stock < $qty) {
                                throw new \RuntimeException('Insufficient stock to complete order for ' . $item->title);
                            }
                            $product->decrement('stock', $qty);
                        }
                    }
                } elseif ($previousStatus === 5 && $newStatus !== 5) {
                    foreach ($orderDetails as $item) {
                        $product = Product::where('title', $item->title)->lockForUpdate()->first();
                        if ($product) {
                            $product->increment('stock', (int) $item->quantity);
                        }
                    }
                }

                $order->update(['status' => $newStatus]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('orderDetail', $order_code)->with('failed', $e->getMessage());
        }

        return redirect()->route('orderDetail', $order_code)->with('success', 'Order status updated.');
    }

    public function delete($order_code)
    {
        DB::transaction(function () use ($order_code) {
            Order::where('order_code', $order_code)->delete();
            OrderDetail::where('order_code', $order_code)->delete();
        });

        return redirect()->route('orders')->with('success', 'Order deleted.');
    }
}
