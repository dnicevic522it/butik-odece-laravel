<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_products(): void
    {
        
        $category = Category::create([
            'name' => 'Test Kategorija',
            'description' => 'Opis'
        ]);

        $product = Product::create([
            'name' => 'Test Proizvod',
            'description' => 'Opis proizvoda',
            'price' => 1000,
            'category_id' => $category->id,
            'gender' => 'unisex',
            'is_active' => true
        ]);

        
        $response = $this->get('/');

        
        $response->assertStatus(200);
    }

    public function test_products_page_works(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }
}