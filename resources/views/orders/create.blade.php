@extends('layouts.app')

@section('title', 'Završi kupovinu - Butik Odeće')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-8">Završi kupovinu</h1>

<div class="grid md:grid-cols-3 gap-8">
    <!-- Forma za checkout -->
    <div class="md:col-span-2">
        <form action="{{ route('orders.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            
            <!-- Skrivena polja za stavke iz korpe -->
            @foreach($cartItems as $index => $item)
                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item['product']->id }}">
                <input type="hidden" name="items[{{ $index }}][size_id]" value="{{ $item['size']->id }}">
                <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
            @endforeach
            
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Adresa za dostavu</h2>
            
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Adresa *</label>
                    <input type="text" name="shipping_address" required
                           value="{{ old('shipping_address', auth()->user()->address) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ulica i broj">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Grad *</label>
                    <input type="text" name="shipping_city" required
                           value="{{ old('shipping_city', auth()->user()->city) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Beograd">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Poštanski broj *</label>
                    <input type="text" name="shipping_postal_code" required
                           value="{{ old('shipping_postal_code', auth()->user()->postal_code) }}"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="11000">
                </div>
            </div>
            
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Način plaćanja</h2>
            
            <div class="space-y-3 mb-6">
                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="cash_on_delivery" checked class="mr-3">
                    <div>
                        <span class="font-medium">Plaćanje pouzećem</span>
                        <p class="text-sm text-gray-500">Platite gotovinom ili karticom prilikom preuzimanja</p>
                    </div>
                </label>
                
                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="card" class="mr-3">
                    <div>
                        <span class="font-medium">Platna kartica</span>
                        <p class="text-sm text-gray-500">Visa, Mastercard, Maestro</p>
                    </div>
                </label>
                
                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="paypal" class="mr-3">
                    <div>
                        <span class="font-medium">PayPal</span>
                        <p class="text-sm text-gray-500">Platite preko PayPal naloga</p>
                    </div>
                </label>
            </div>
            
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Napomena (opciono)</h2>
            
            <div class="mb-6">
                <textarea name="notes" rows="3"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Dodatne napomene za dostavu...">{{ old('notes') }}</textarea>
            </div>
            
            <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold text-lg hover:bg-green-600 transition">
                Potvrdi narudžbinu
            </button>
        </form>
    </div>
    
    <!-- Pregled narudžbine -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Vaša narudžbina</h2>
            
            <div class="space-y-4 mb-6">
                @foreach($cartItems as $item)
                    <div class="flex items-center">
                        @if($item['product']->image_url)
                            <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" 
                                 class="w-12 h-12 object-cover rounded mr-3">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded mr-3"></div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 text-sm">{{ $item['product']->name }}</p>
                            <p class="text-xs text-gray-500">Veličina: {{ $item['size']->size }} × {{ $item['quantity'] }}</p>
                        </div>
                        <p class="font-semibold text-gray-800">{{ number_format($item['subtotal'], 2) }} RSD</p>
                    </div>
                @endforeach
            </div>
            
            <div class="border-t pt-4">
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Međuzbir:</span>
                    <span>{{ number_format($total, 2) }} RSD</span>
                </div>
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Dostava:</span>
                    <span class="text-green-600">Besplatno</span>
                </div>
                <div class="flex justify-between font-bold text-xl text-gray-800 mt-4 pt-4 border-t">
                    <span>Ukupno:</span>
                    <span class="text-blue-600">{{ number_format($total, 2) }} RSD</span>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="{{ route('cart') }}" class="block text-center text-blue-600 hover:underline">
                    ← Nazad na korpu
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
