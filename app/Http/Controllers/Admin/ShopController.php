<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_shop' => 'required|string|max:120',
            'phone' => 'required|numeric',
            'address' => 'required|string|max:255',
            'path' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'desc' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home')->withErrors($validator)->withInput();
        }

        $file = $request->file('path');
        $fileName = Str::random(24) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('shop'), $fileName);

        Shop::create([
            'user_id' => Auth::id(),
            'name_shop' => $request->name_shop,
            'phone' => $request->phone,
            'address' => $request->address,
            'desc' => $request->desc,
            'path' => $fileName,
        ]);

        return redirect()->route('home')->with('success', 'Shop created.');
    }

    public function detail()
    {
        $shop = Auth::user()->shop ?? Shop::firstOrFail();

        return view('admin.shop.detail', [
            'title' => 'Shop Detail',
            'shop' => $shop,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_shop' => 'required|string|max:120',
            'phone' => 'required|numeric',
            'address' => 'required|string|max:255',
            'desc' => 'required|string|max:500',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($validator->fails()) {
            return redirect()->route('shopDetail')->withErrors($validator)->withInput();
        }

        $shop = Auth::user()->shop;
        $payload = [
            'name_shop' => $request->name_shop,
            'phone' => $request->phone,
            'address' => $request->address,
            'desc' => $request->desc,
        ];

        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $fileName = Str::random(24) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('shop'), $fileName);

            $oldPath = public_path('shop/' . basename($shop->path));
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }

            $payload['path'] = $fileName;
        }

        $shop->update($payload);

        return redirect()->route('shopDetail')->with('success', 'Shop updated.');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home')->with('failed', 'Failed to update password.')->withErrors($validator)->withInput();
        }

        User::where('id', Auth::id())->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Password changed.');
    }
}
