<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderItemStoreRequest;
use App\Http\Requests\OrderItemUpdateRequest;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderItemController extends Controller
{
    public function index(Request $request): Response
    {
        $orderItems = OrderItem::all();

        return view('orderItem.index', [
            'orderItems' => $orderItems,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('orderItem.create');
    }

    public function store(OrderItemStoreRequest $request): Response
    {
        $orderItem = OrderItem::create($request->validated());

        $request->session()->flash('orderItem.id', $orderItem->id);

        return redirect()->route('orderItems.index');
    }

    public function show(Request $request, OrderItem $orderItem): Response
    {
        return view('orderItem.show', [
            'orderItem' => $orderItem,
        ]);
    }

    public function edit(Request $request, OrderItem $orderItem): Response
    {
        return view('orderItem.edit', [
            'orderItem' => $orderItem,
        ]);
    }

    public function update(OrderItemUpdateRequest $request, OrderItem $orderItem): Response
    {
        $orderItem->update($request->validated());

        $request->session()->flash('orderItem.id', $orderItem->id);

        return redirect()->route('orderItems.index');
    }

    public function destroy(Request $request, OrderItem $orderItem): Response
    {
        $orderItem->delete();

        return redirect()->route('orderItems.index');
    }
}
