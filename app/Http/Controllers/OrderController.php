<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     * Prikazuje listu narudžbina korisnika
     */
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order (checkout).
     * Prikazuje checkout formu
     */
    public function create()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')
                ->with('error', 'Vaša korpa je prazna.');
        }

        $cartItems = $this->getCartItems($cart);
        $total = $this->calculateTotal($cartItems);

        return view('orders.create', compact('cartItems', 'total'));
    }

    /**
     * Store a newly created order in storage.
     * UC-1: Naručivanje proizvoda
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,card,paypal',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size_id' => 'required|exists:sizes,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;

        // Kreiraj narudžbinu
        $order = Order::create([
            'order_number' => 'ORD-'.strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_amount' => 0,
            'shipping_address' => $validated['shipping_address'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Dodaj stavke i proveri zalihe
        foreach ($validated['items'] as $item) {
            $size = Size::findOrFail($item['size_id']);
            $product = $size->product;

            // Proveri da li ima dovoljno na stanju
            if ($size->quantity_in_stock < $item['quantity']) {
                // Rollback - obriši narudžbinu
                $order->delete();

                return back()->withErrors([
                    'items' => 'Nema dovoljno na stanju za proizvod: '.$product->name.' (veličina: '.$size->size.')',
                ]);
            }

            // Smanji zalihe
            $size->decrement('quantity_in_stock', $item['quantity']);

            // Kreiraj stavku narudžbine
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            $totalAmount += $product->price * $item['quantity'];
        }

        // Ažuriraj ukupan iznos
        $order->update(['total_amount' => $totalAmount]);

        // Očisti korpu
        session()->forget('cart');

        return redirect()->route('orders.show', $order)
            ->with('success', 'Narudžbina uspešno kreirana! Broj narudžbine: '.$order->order_number);
    }

    /**
     * Display the specified order.
     * Prikazuje detalje narudžbine
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'items.size', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     * UC-3: Otkazivanje narudžbine
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);

        // Dodatna provera statusa
        if ($order->status !== 'pending') {
            abort(403, 'Samo narudžbine na čekanju mogu biti otkazane.');
        }

        // Vrati zalihe za sve stavke
        foreach ($order->items as $item) {
            $item->size->increment('quantity_in_stock', $item->quantity);
        }

        // Promeni status u cancelled
        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')
            ->with('success', 'Narudžbina '.$order->order_number.' je uspešno otkazana.');
    }

    /**
     * Display cart contents.
     * Prikazuje sadržaj korpe
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $cartItems = $this->getCartItems($cart);
        $total = $this->calculateTotal($cartItems);

        return view('orders.cart', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart.
     * Dodaje proizvod u korpu
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Proveri da li veličina pripada proizvodu
        $size = Size::where('id', $validated['size_id'])
            ->where('product_id', $validated['product_id'])
            ->firstOrFail();

        // Proveri zalihe
        if ($size->quantity_in_stock < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'Nema dovoljno na stanju. Dostupno: '.$size->quantity_in_stock,
            ]);
        }

        $cart = session()->get('cart', []);
        $key = $validated['product_id'].'-'.$validated['size_id'];

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $validated['quantity'];
        } else {
            $cart[$key] = [
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
                'quantity' => $validated['quantity'],
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Proizvod dodat u korpu!');
    }

    /**
     * Remove item from cart.
     * Uklanja proizvod iz korpe
     */
    public function removeFromCart(Request $request)
    {
        $key = $request->input('key');
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Proizvod uklonjen iz korpe.');
    }

    /**
     * Update cart quantity.
     * Ažurira količinu u korpi
     */
    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$validated['key']])) {
            $cart[$validated['key']]['quantity'] = $validated['quantity'];
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Korpa ažurirana.');
    }

    /**
     * Helper: Get cart items with product details.
     */
    private function getCartItems(array $cart): array
    {
        $items = [];

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            $size = Size::find($item['size_id']);

            if ($product && $size) {
                $items[] = [
                    'key' => $key,
                    'product' => $product,
                    'size' => $size,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
            }
        }

        return $items;
    }

    /**
     * Helper: Calculate cart total.
     */
    private function calculateTotal(array $cartItems): float
    {
        return array_sum(array_column($cartItems, 'subtotal'));
    }

    // ==========================================
    // ADMIN METODE (za resource controller)
    // ==========================================

    /**
     * Show the form for editing the specified order (admin).
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage (admin).
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status narudžbine ažuriran.');
    }

    /**
     * Remove the specified order from storage (admin).
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Narudžbina obrisana.');
    }
}
