@extends('layouts.app')

@section('title', 'Admin Panel - Butik Odeće')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Panel</h1>

<div class="grid md:grid-cols-4 gap-6 mb-8">
    <!-- Statistike -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Ukupno proizvoda</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\Product::count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Ukupno narudžbina</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\Order::count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Na čekanju</h3>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ \App\Models\Order::where('status', 'pending')->count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Korisnika</h3>
        <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::count() }}</p>
    </div>
</div>

<!-- Meni -->
<div class="grid md:grid-cols-3 gap-6">
    <a href="{{ route('admin.products.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">📦 Proizvodi</h3>
        <p class="text-gray-600">Upravljanje proizvodima - dodavanje, izmena, brisanje</p>
    </a>
    
    <a href="{{ route('admin.categories.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">🏷️ Kategorije</h3>
        <p class="text-gray-600">Upravljanje kategorijama proizvoda</p>
    </a>
    
    <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">📋 Narudžbine</h3>
        <p class="text-gray-600">Pregled i obrada narudžbina</p>
    </a>
    
    <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">👥 Korisnici</h3>
        <p class="text-gray-600">Upravljanje korisnicima sistema</p>
    </a>
    
    <a href="{{ route('admin.sizes.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">📏 Veličine/Zalihe</h3>
        <p class="text-gray-600">Upravljanje veličinama i zalihama</p>
    </a>
</div>

<!-- Nedavne narudžbine -->
<div class="mt-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Nedavne narudžbine</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Broj</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kupac</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Iznos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach(\App\Models\Order::with('user')->latest()->take(5)->get() as $order)
                    <tr>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @switch($order->status)
                                @case('pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Na čekanju</span>
                                    @break
                                @case('processing')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">U obradi</span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Poslato</span>
                                    @break
                                @case('delivered')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Isporučeno</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Otkazano</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ number_format($order->total_amount, 2) }} RSD</td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
