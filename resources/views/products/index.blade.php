@extends('layouts.app')

@section('title', 'Proizvodi - Butik Odeće')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Naši proizvodi</h1>
    <p class="text-gray-600 mt-2">Pronađite savršenu odeću za vas</p>
</div>

<!-- Filteri -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-4">
        <select name="category" class="border rounded px-3 py-2">
            <option value="">Sve kategorije</option>
            @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        
        <select name="gender" class="border rounded px-3 py-2">
            <option value="">Svi polovi</option>
            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Muško</option>
            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Žensko</option>
            <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
        </select>
        
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Filtriraj
        </button>
    </form>
</div>

<!-- Lista proizvoda -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($products ?? [] as $product)
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
            <a href="{{ route('products.show', $product) }}">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">Nema slike</span>
                    </div>
                @endif
            </a>
            
            <div class="p-4">
                <a href="{{ route('products.show', $product) }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600">
                    {{ $product->name }}
                </a>
                <p class="text-gray-600 text-sm mt-1">{{ $product->category->name ?? 'Bez kategorije' }}</p>
                <p class="text-blue-600 font-bold mt-2">{{ number_format($product->price, 2) }} RSD</p>
                
                <a href="{{ route('products.show', $product) }}" 
                   class="mt-3 block text-center bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                    Pogledaj
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 text-lg">Nema proizvoda za prikaz.</p>
        </div>
    @endforelse
</div>

<!-- Paginacija -->
@if(isset($products) && $products->hasPages())
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
@endsection
