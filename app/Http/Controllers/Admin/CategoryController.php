<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index', [
            'title' => 'Category',
            'category' => Category::orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.category.create', [
            'title' => 'Create category',
        ]);
    }

    public function check(Request $request)
    {
        $exists = Category::where('name', $request->name)->exists();

        return $exists
            ? response()->json(['status' => 'success', 'messages' => 'not available'], 200)
            : response()->json(['status' => 'success', 'messages' => 'available'], 201);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return redirect()->route('categoryCreate')->withErrors($validator)->withInput();
        }

        $file = $request->file('path');
        $fileName = Str::random(24) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('shop/products'), $fileName);

        Category::create([
            'shop_id' => Auth::user()->shop->id,
            'name' => $request->name,
            'path' => $fileName,
        ]);

        return redirect()->route('category')->with('success', 'Category created.');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        $filePath = public_path('shop/products/' . basename($category->path));
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        $category->delete();

        return redirect()->route('category')->with('success', 'Category deleted.');
    }
}
