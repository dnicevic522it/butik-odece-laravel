<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created(): void
    {
        $category = Category::create([
            'name' => 'Nova Kategorija',
            'description' => 'Test opis'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Nova Kategorija'
        ]);
    }

    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }
}