
@extends('layouts.app')

@section('title', 'Korpa - Butik Odeće')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-8">Vaša korpa</h1>

@if(empty($cartItems))
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500 text-lg mb-4">Vaša korpa je prazna.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Pregledajte proizvode
        </a>
    </div>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proizvod</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veličina</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cena</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Količina</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukupno</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akcije</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($cartItems as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($item['product']->image_url)
                                    <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="w-16 h-16 object-cover rounded mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">Nema slike</span>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('products.show', $item['product']) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                        {{ $item['product']->name }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $item['product']->category->name ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $item['size']->size }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ number_format($item['product']->price, 2) }} RSD</td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">{{ $item['quantity'] }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            {{ number_format($item['subtotal'], 2) }} RSD
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{ $item['key'] }}">
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Ukloni
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ukupno i dugmad -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <span class="text-xl font-semibold text-gray-800">Ukupno:</span>
            <span class="text-2xl font-bold text-blue-600">{{ number_format($total, 2) }} RSD</span>
        </div>
        <div class="flex justify-between items-center">
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">
                ← Nastavi kupovinu
            </a>
            <a href="{{ route('checkout') }}" class="bg-green-500 text-black px-8 py-3 rounded-lg font-semibold text-lg hover:bg-green-600 transition">
                Nastavi na plaćanje →
            </a>
        </div>
    </div>
@endif
@endsection