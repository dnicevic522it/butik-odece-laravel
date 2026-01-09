@extends('layouts.app')

@section('title', 'Moje narudžbine - Butik Odeće')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-8">Moje narudžbine</h1>

@if($orders->isEmpty())
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500 text-lg">Nemate nijednu narudžbinu.</p>
        <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Pregledajte proizvode
        </a>
    </div>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Broj narudžbine</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukupno</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akcije</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($order->status)
                                @case('pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Na čekanju
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        U obradi
                                    </span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Poslato
                                    </span>
                                    @break
                                @case('delivered')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Isporučeno
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Otkazano
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">
                            {{ number_format($order->total_amount, 2) }} RSD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">
                                Detalji
                            </a>
                            
                            @if($order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline ml-4"
                                      onsubmit="return confirm('Da li ste sigurni da želite da otkažete narudžbinu?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Otkaži
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif
@endsection
