@extends('layouts.app')

@section('title', $product->name . ' - Butik Odeće')

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="md:flex">
        <!-- Slika proizvoda -->
        <div class="md:w-1/2">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
            @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 text-xl">Nema slike</span>
                </div>
            @endif
        </div>
        
        <!-- Detalji proizvoda -->
        <div class="md:w-1/2 p-8">
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Početna</a> /
                <a href="{{ route('products.index') }}" class="hover:text-blue-600">Proizvodi</a> /
                <span>{{ $product->name }}</span>
            </nav>
            
            <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
            
            <p class="text-gray-600 mt-2">
                Kategorija: {{ $product->category->name ?? 'Bez kategorije' }}
            </p>
            
            <p class="text-3xl font-bold text-blue-600 mt-4">
                {{ number_format($product->price, 2) }} RSD
            </p>
            
            <div class="mt-6">
                <h3 class="font-semibold text-gray-800">Opis:</h3>
                <p class="text-gray-600 mt-2">{{ $product->description ?? 'Nema opisa.' }}</p>
            </div>
            
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-semibold">Pol:</span>
                    <span class="text-gray-600">
                        @switch($product->gender)
                            @case('male') Muško @break
                            @case('female') Žensko @break
                            @default Unisex
                        @endswitch
                    </span>
                </div>
                <div>
                    <span class="font-semibold">Boja:</span>
                    <span class="text-gray-600">{{ $product->color ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-semibold">Materijal:</span>
                    <span class="text-gray-600">{{ $product->material ?? 'N/A' }}</span>
                </div>
            </div>
            
            <!-- Forma za dodavanje u korpu -->
            @auth
                <form action="{{ route('cart.add') }}" method="POST" class="mt-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-800 mb-2">Veličina:</label>
                        <select name="size_id" required class="w-full border rounded px-3 py-2">
                            <option value="">Izaberite veličinu</option>
                            @foreach($product->sizes as $size)
                                <option value="{{ $size->id }}" {{ $size->quantity_in_stock == 0 ? 'disabled' : '' }}>
                                    {{ $size->size }} 
                                    ({{ $size->quantity_in_stock > 0 ? 'Na stanju: ' . $size->quantity_in_stock : 'Nema na stanju' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-800 mb-2">Količina:</label>
                        <input type="number" name="quantity" value="1" min="1" max="10" 
                               class="w-24 border rounded px-3 py-2">
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                        🛒 Dodaj u korpu
                    </button>
                </form>
            @else
                <div class="mt-8 p-4 bg-gray-100 rounded-lg text-center">
                    <p class="text-gray-600">Morate biti prijavljeni da biste kupovali.</p>
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Prijavite se</a>
                </div>
            @endauth
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">
        ← Nazad na proizvode
    </a>
</div>
@endsection
