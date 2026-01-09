@extends('layouts.app')

@section('title', 'Narudžbina ' . $order->order_number . ' - Butik Odeće')

@section('content')
<div class="mb-4">
    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">
        ← Nazad na moje narudžbine
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Narudžbina {{ $order->order_number }}</h1>
                <p class="text-gray-600">Kreirana: {{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                @switch($order->status)
                    @case('pending')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Na čekanju
                        </span>
                        @break
                    @case('processing')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            U obradi
                        </span>
                        @break
                    @case('shipped')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                            Poslato
                        </span>
                        @break
                    @case('delivered')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Isporučeno
                        </span>
                        @break
                    @case('cancelled')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Otkazano
                        </span>
                        @break
                @endswitch
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Informacije o dostavi -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Adresa za dostavu</h3>
                <p class="text-gray-600">
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_postal_code }} {{ $order->shipping_city }}
                </p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-2">Način plaćanja</h3>
                <p class="text-gray-600">
                    @switch($order->payment_method)
                        @case('cash_on_delivery') Plaćanje pouzećem @break
                        @case('card') Kartica @break
                        @case('paypal') PayPal @break
                    @endswitch
                </p>
            </div>
        </div>
        
        @if($order->notes)
            <div class="mb-8">
                <h3 class="font-semibold text-gray-800 mb-2">Napomena</h3>
                <p class="text-gray-600">{{ $order->notes }}</p>
            </div>
        @endif
        
        <!-- Stavke narudžbine -->
        <h3 class="font-semibold text-gray-800 mb-4">Stavke narudžbine</h3>
        <div class="border rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proizvod</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veličina</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Količina</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukupno</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                             class="w-12 h-12 object-cover rounded mr-4">
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->size->size }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ number_format($item->price, 2) }} RSD</td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ number_format($item->price * $item->quantity, 2) }} RSD
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-800">
                            UKUPNO:
                        </td>
                        <td class="px-6 py-4 font-bold text-xl text-blue-600">
                            {{ number_format($order->total_amount, 2) }} RSD
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Akcije -->
        @if($order->status === 'pending')
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <p class="text-yellow-800 mb-4">Narudžbina je na čekanju. Možete je otkazati dok nije odobrena.</p>
                <form action="{{ route('orders.cancel', $order) }}" method="POST"
                      onsubmit="return confirm('Da li ste sigurni da želite da otkažete narudžbinu?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                        Otkaži narudžbinu
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
