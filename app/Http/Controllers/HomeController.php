<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->shop) {
            return view('admin.shop.create');
        }

        $data = [
            'title' => 'Dashboard',
            'product' => Product::count(),
            'category' => Category::count(),
            'sales' => Order::where('status', 5)->sum('total'),
            'order' => Order::where('status', 5)->count(),
            'newOrder' => Order::orderByDesc('id')->take(5)->get(),
        ];

        return view('admin.index', $data);
    }
}
