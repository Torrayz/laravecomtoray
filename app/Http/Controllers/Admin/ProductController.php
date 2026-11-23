<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index', [
            'products' => Product::with('category')->orderByDesc('id')->get(),
            'title' => 'Products',
        ]);
    }

    public function create()
    {
        return view('admin.product.create', ['title' => 'Add Product']);
    }

    public function check(Request $request)
    {
        $exists = Product::where('title', $request->title)->exists();

        return $exists
            ? response()->json(['status' => 'success', 'messages' => 'not available', 'code' => 200], 200)
            : response()->json(['status' => 'success', 'messages' => 'available', 'code' => 201], 201);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255|unique:products,title',
            'price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'desc' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('productCreate')->withErrors($validator)->withInput();
        }

        $product = Product::create([
            'category_id' => $request->category,
            'title' => $request->title,
            'price' => $request->price,
            'stock' => $request->stock,
            'desc' => $request->desc,
        ]);

        return redirect()->route('productAddImages', ['product' => $product->title]);
    }

    public function addImages($product)
    {
        $productData = Product::where('title', $product)->firstOrFail();

        return view('admin.product.addImages', [
            'title' => 'Add Images - ' . str_replace('-', ' ', $productData->title),
            'product' => $productData,
        ]);
    }

    public function getImages(Request $request)
    {
        $request->validate([
            'id_products' => 'required|integer',
        ]);

        $data = ProductImage::where('product_id', $request->id_products)
            ->orderByDesc('id')
            ->get();

        return response()->json($data);
    }

    public function addImagesSave(Request $request)
    {
        $request->validate([
            'id_product' => 'required|integer|exists:products,id',
            'file' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $image = $request->file('file');
        $imageName = Str::random(24) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('shop/products'), $imageName);

        ProductImage::create([
            'product_id' => $request->id_product,
            'path' => $imageName,
        ]);

        return response()->json(['status' => 'success', 'code' => 200], 200);
    }

    public function deleteImages(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = basename($request->input('filename'));
        ProductImage::where('path', $filename)->delete();

        $filePath = public_path('shop/products/' . $filename);
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        return response()->json(['status' => 'success', 'code' => 200], 200);
    }

    public function edit($product)
    {
        $productData = Product::where('title', $product)->firstOrFail();

        return view('admin.product.edit', [
            'product' => $productData,
            'title' => 'Edit product ' . str_replace('-', ' ', $productData->title),
        ]);
    }

    public function editSave(Request $request, $product, $id)
    {
        $productModel = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255|unique:products,title,' . $productModel->id,
            'price' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'desc' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('productEdit', ['product' => $product])->withErrors($validator)->withInput();
        }

        $productModel->update([
            'category_id' => $request->category,
            'title' => $request->title,
            'price' => $request->price,
            'stock' => $request->stock,
            'desc' => $request->desc,
        ]);

        return redirect()->route('productEdit', $productModel->title)->with('success', 'Product updated.');
    }

    public function delete($id)
    {
        $product = Product::with('productImage')->findOrFail($id);

        $filesToDelete = [];
        foreach ($product->productImage as $item) {
            $filePath = public_path('shop/products/' . basename($item->path));
            if (is_file($filePath)) {
                $filesToDelete[] = $filePath;
            }
        }

        if (!empty($filesToDelete)) {
            File::delete($filesToDelete);
        }

        $product->delete();

        return redirect()->route('products')->with('success', 'Product deleted.');
    }
}
