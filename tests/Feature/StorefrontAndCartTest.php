<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontAndCartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_storefront_pages_render_successfully()
    {
        $this->get(route('clientProducts'))->assertOk();
        $this->get(route('clientCategory'))->assertOk();
        $this->get(route('clientAbout'))->assertOk();
        $this->get(route('clientCheckOrder'))->assertOk();
        $this->get(route('clientProductDetail', 'galaxy-essential-smartphone'))->assertOk();
        $this->get(route('clientCategoryProducts', 'electronics'))->assertOk();
    }

    public function test_registration_is_closed_once_an_admin_user_exists()
    {
        $this->get(route('register'))->assertRedirect(route('login'));
        $this->post(route('register'), [
            'name' => 'Unauthorized User',
            'email' => 'attacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('users', ['email' => 'attacker@example.com']);
    }

    public function test_cart_and_checkout_use_database_prices_and_create_order()
    {
        $product = Product::where('title', 'galaxy-essential-smartphone')->firstOrFail();

        $this->postJson(route('clientAddToCart'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertStatus(200)
          ->assertJson(['status' => 'success', 'cartCount' => 1]);

        // Tamper session price to verify checkout computes total strictly from DB
        $cart = session('cart');
        $cart[$product->id]['price'] = 1;
        session()->put('cart', $cart);

        $response = $this->post(route('clientCheckoutSave'), [
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'address' => 'Jl. Merdeka No. 10, Tangerang',
            'note' => 'Deliver before 5 PM',
        ]);

        $order = Order::firstOrFail();
        $response->assertRedirect(route('clientOrderCode', $order->order_code));

        $this->assertSame((int) ($product->price * 2), (int) $order->total);
        $this->assertEmpty(session('cart'));

        $this->post(route('clientCheckOrderStatus'), [
            'order_code' => $order->order_code,
        ])->assertOk()->assertSee($order->order_code);
    }

    public function test_admin_order_completion_adjusts_stock_and_reverting_restores_stock()
    {
        $admin = User::firstOrFail();
        $product = Product::where('title', 'redmi-daily-smartphone')->firstOrFail();
        $initialStock = (int) $product->stock;

        $this->postJson(route('clientAddToCart'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ])->assertStatus(200);

        $this->post(route('clientCheckoutSave'), [
            'name' => 'Siti Aminah',
            'phone' => '081299988877',
            'address' => 'Jakarta Selatan',
        ]);

        $order = Order::firstOrFail();

        $this->actingAs($admin)
            ->post(route('orderUpdateStatus', $order->order_code), ['status' => 5])
            ->assertRedirect(route('orderDetail', $order->order_code));

        $this->assertSame($initialStock - 3, (int) $product->fresh()->stock);

        $this->actingAs($admin)
            ->post(route('orderUpdateStatus', $order->order_code), ['status' => 2])
            ->assertRedirect(route('orderDetail', $order->order_code));

        $this->assertSame($initialStock, (int) $product->fresh()->stock);

        $this->actingAs($admin)
            ->delete(route('orderDelete', $order->order_code))
            ->assertRedirect(route('orders'));

        $this->assertDatabaseMissing('orders', ['order_code' => $order->order_code]);
    }

    public function test_admin_routes_require_authentication_and_delete_category_uses_delete_method()
    {
        $this->get(route('products'))->assertRedirect(route('login'));

        $admin = User::firstOrFail();
        $category = Category::create([
            'shop_id' => 1,
            'name' => 'temp-test-category',
            'path' => 'nonexistent-test-category.png',
        ]);

        $this->actingAs($admin)
            ->delete(route('categoryDelete', $category->id))
            ->assertRedirect(route('category'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
