<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('is_active', true)->paginate(12);
        $categories = Category::all();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        return view('product.create');
    }

    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());

        $request->session()->flash('product.id', $product->id);

        return redirect()->route('products.index');
    }

    public function show(Request $request, Product $product)
{
    $product->load(['category', 'sizes']);
    
    return view('products.show', [
        'product' => $product,
    ]);
}

    public function edit(Request $request, Product $product)
    {
        return view('product.edit', [
            'product' => $product,
        ]);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());

        $request->session()->flash('product.id', $product->id);

        return redirect()->route('products.index');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        return redirect()->route('products.index');
    }
}
