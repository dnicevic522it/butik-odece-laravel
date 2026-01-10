<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@butik.rs',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kupac Test',
            'email' => 'kupac@test.rs',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $kosulје = Category::create(['name' => 'Košulje', 'description' => 'Muške i ženske košulje']);
        $pantalone = Category::create(['name' => 'Pantalone', 'description' => 'Muške i ženske pantalone']);
        $haljine = Category::create(['name' => 'Haljine', 'description' => 'Elegantne haljine']);

        $p1 = Product::create([
            'name' => 'Bela košulja',
            'description' => 'Elegantna bela košulja za poslovne prilike',
            'price' => 3500,
            'category_id' => $kosulје->id,
            'gender' => 'unisex',
            'color' => 'Bela',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p1->id, 'size' => 'S', 'quantity_in_stock' => 5]);
        Size::create(['product_id' => $p1->id, 'size' => 'M', 'quantity_in_stock' => 10]);
        Size::create(['product_id' => $p1->id, 'size' => 'L', 'quantity_in_stock' => 8]);

        $p2 = Product::create([
            'name' => 'Plava košulja',
            'description' => 'Casual plava košulja za svaki dan',
            'price' => 2900,
            'category_id' => $kosulје->id,
            'gender' => 'unisex',
            'color' => 'Plava',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p2->id, 'size' => 'M', 'quantity_in_stock' => 12]);
        Size::create(['product_id' => $p2->id, 'size' => 'L', 'quantity_in_stock' => 7]);

        $p3 = Product::create([
            'name' => 'Crni sako',
            'description' => 'Elegantan crni sako za posebne prilike',
            'price' => 8500,
            'category_id' => $kosulје->id,
            'gender' => 'male',
            'color' => 'Crna',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p3->id, 'size' => 'L', 'quantity_in_stock' => 6]);
        Size::create(['product_id' => $p3->id, 'size' => 'XL', 'quantity_in_stock' => 4]);

        $p4 = Product::create([
            'name' => 'Crne pantalone',
            'description' => 'Elegantne crne pantalone',
            'price' => 4500,
            'category_id' => $pantalone->id,
            'gender' => 'male',
            'color' => 'Crna',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p4->id, 'size' => 'M', 'quantity_in_stock' => 8]);
        Size::create(['product_id' => $p4->id, 'size' => 'L', 'quantity_in_stock' => 10]);

        $p5 = Product::create([
            'name' => 'Farmerke slim fit',
            'description' => 'Moderne slim fit farmerke',
            'price' => 5500,
            'category_id' => $pantalone->id,
            'gender' => 'unisex',
            'color' => 'Plava',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p5->id, 'size' => 'S', 'quantity_in_stock' => 6]);
        Size::create(['product_id' => $p5->id, 'size' => 'M', 'quantity_in_stock' => 15]);
        Size::create(['product_id' => $p5->id, 'size' => 'L', 'quantity_in_stock' => 10]);

        $p6 = Product::create([
            'name' => 'Crvena haljina',
            'description' => 'Prelepa crvena haljina za izlaske',
            'price' => 6500,
            'category_id' => $haljine->id,
            'gender' => 'female',
            'color' => 'Crvena',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p6->id, 'size' => 'S', 'quantity_in_stock' => 5]);
        Size::create(['product_id' => $p6->id, 'size' => 'M', 'quantity_in_stock' => 7]);

        $p7 = Product::create([
            'name' => 'Letnja haljina',
            'description' => 'Lagana letnja haljina sa cvetnim dezenom',
            'price' => 4200,
            'category_id' => $haljine->id,
            'gender' => 'female',
            'color' => 'Sarena',
            'is_active' => true,
        ]);
        Size::create(['product_id' => $p7->id, 'size' => 'M', 'quantity_in_stock' => 9]);
        Size::create(['product_id' => $p7->id, 'size' => 'L', 'quantity_in_stock' => 6]);
    }
}
