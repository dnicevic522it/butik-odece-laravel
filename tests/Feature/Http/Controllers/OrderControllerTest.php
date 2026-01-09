<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController
 */
final class OrderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->get(route('orders.index'));

        $response->assertOk();
        $response->assertViewIs('order.index');
        $response->assertViewHas('orders', $orders);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('orders.create'));

        $response->assertOk();
        $response->assertViewIs('order.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'store',
            \App\Http\Requests\OrderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $order_number = fake()->word();
        $user = User::factory()->create();
        $status = fake()->randomElement(/** enum_attributes **/);
        $total_amount = fake()->randomFloat(/** decimal_attributes **/);
        $shipping_address = fake()->word();
        $shipping_city = fake()->word();
        $shipping_postal_code = fake()->word();
        $payment_method = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('orders.store'), [
            'order_number' => $order_number,
            'user_id' => $user->id,
            'status' => $status,
            'total_amount' => $total_amount,
            'shipping_address' => $shipping_address,
            'shipping_city' => $shipping_city,
            'shipping_postal_code' => $shipping_postal_code,
            'payment_method' => $payment_method,
        ]);

        $orders = Order::query()
            ->where('order_number', $order_number)
            ->where('user_id', $user->id)
            ->where('status', $status)
            ->where('total_amount', $total_amount)
            ->where('shipping_address', $shipping_address)
            ->where('shipping_city', $shipping_city)
            ->where('shipping_postal_code', $shipping_postal_code)
            ->where('payment_method', $payment_method)
            ->get();
        $this->assertCount(1, $orders);
        $order = $orders->first();

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('order.id', $order->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.show', $order));

        $response->assertOk();
        $response->assertViewIs('order.show');
        $response->assertViewHas('order', $order);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.edit', $order));

        $response->assertOk();
        $response->assertViewIs('order.edit');
        $response->assertViewHas('order', $order);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'update',
            \App\Http\Requests\OrderUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $order = Order::factory()->create();
        $order_number = fake()->word();
        $user = User::factory()->create();
        $status = fake()->randomElement(/** enum_attributes **/);
        $total_amount = fake()->randomFloat(/** decimal_attributes **/);
        $shipping_address = fake()->word();
        $shipping_city = fake()->word();
        $shipping_postal_code = fake()->word();
        $payment_method = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('orders.update', $order), [
            'order_number' => $order_number,
            'user_id' => $user->id,
            'status' => $status,
            'total_amount' => $total_amount,
            'shipping_address' => $shipping_address,
            'shipping_city' => $shipping_city,
            'shipping_postal_code' => $shipping_postal_code,
            'payment_method' => $payment_method,
        ]);

        $order->refresh();

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('order.id', $order->id);

        $this->assertEquals($order_number, $order->order_number);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($status, $order->status);
        $this->assertEquals($total_amount, $order->total_amount);
        $this->assertEquals($shipping_address, $order->shipping_address);
        $this->assertEquals($shipping_city, $order->shipping_city);
        $this->assertEquals($shipping_postal_code, $order->shipping_postal_code);
        $this->assertEquals($payment_method, $order->payment_method);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders.index'));

        $this->assertModelMissing($order);
    }
}
