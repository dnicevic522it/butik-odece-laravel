<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Kupac može da otkaže nepotvđenu narudžbinu (UC-3)
     */
    public function test_customer_can_cancel_pending_order(): void
    {
        // Arrange
        $customer = User::factory()->create(['role' => 'customer']);

        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $size = Size::factory()->create([
            'product_id' => $product->id,
            'quantity_in_stock' => 5, // Početna zaliha nakon kreiranja narudžbine
        ]);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'pending',
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 2,
        ]);

        // Act
        $this->actingAs($customer);

        $response = $this->patch(route('orders.cancel', $order), [
            'confirm' => true,
        ]);

        // Assert
        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);

        // Provera da je zaliha vraćena
        $this->assertEquals(7, $size->fresh()->quantity_in_stock);
    }

    /**
     * Test: Kupac NE može da otkaže potvrđenu narudžbinu
     */
    public function test_customer_cannot_cancel_processing_order(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'processing', // Već u obradi
        ]);

        $this->actingAs($customer);

        $response = $this->patch(route('orders.cancel', $order), [
            'confirm' => true,
        ]);

        $response->assertForbidden();

        // Status ostaje isti
        $this->assertEquals('processing', $order->fresh()->status);
    }

    /**
     * Test: Kupac ne može da otkaže tuđu narudžbinu
     */
    public function test_customer_cannot_cancel_another_users_order(): void
    {
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);

        $order = Order::factory()->create([
            'user_id' => $customer1->id,
            'status' => 'pending',
        ]);

        $this->actingAs($customer2);

        $response = $this->patch(route('orders.cancel', $order), [
            'confirm' => true,
        ]);

        $response->assertForbidden();
    }
}
