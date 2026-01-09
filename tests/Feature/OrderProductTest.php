<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Kupac može da kreira narudžbinu (UC-1)
     */
    public function test_customer_can_create_order(): void
    {
        // Arrange - Priprema podataka
        $customer = User::factory()->create([
            'role' => 'customer',
            'address' => 'Bulevar Oslobođenja 50',
            'city' => 'Beograd',
            'postal_code' => '11000',
        ]);

        $category = Category::factory()->create([
            'name' => 'Košulje',
        ]);

        $product = Product::factory()->create([
            'name' => 'Bela elegantna košulja',
            'price' => 3500.00,
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $size = Size::factory()->create([
            'product_id' => $product->id,
            'size' => 'M',
            'quantity_in_stock' => 10,
        ]);

        // Act - Kreiranje narudžbine
        $this->actingAs($customer);

        $response = $this->post(route('orders.store'), [
            'shipping_address' => $customer->address,
            'shipping_city' => $customer->city,
            'shipping_postal_code' => $customer->postal_code,
            'payment_method' => 'cash_on_delivery',
            'items' => [
                [
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        // Assert - Provera rezultata
        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'status' => 'pending',
            'shipping_address' => $customer->address,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ]);

        // Provera da je zaliha smanjena
        $this->assertEquals(9, $size->fresh()->quantity_in_stock);
    }

    /**
     * Test: Kupac može da vidi svoje narudžbine
     */
    public function test_customer_can_view_own_orders(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = Order::factory()->create(['user_id' => $customer->id]);

        $this->actingAs($customer);

        $response = $this->get(route('orders.index'));

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }

    /**
     * Test: Neregistrovani korisnik ne može da naruči
     */
    public function test_guest_cannot_create_order(): void
    {
        $response = $this->post(route('orders.store'), [
            'shipping_address' => 'Test adresa',
            'shipping_city' => 'Beograd',
            'shipping_postal_code' => '11000',
            'payment_method' => 'cash_on_delivery',
            'items' => [],
        ]);

        $response->assertRedirect(route('login'));
    }
}
