<?php

namespace App\Http\Controllers;

use App\Http\Requests\SizeStoreRequest;
use App\Http\Requests\SizeUpdateRequest;
use App\Models\Size;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SizeController extends Controller
{
    public function index(Request $request): Response
    {
        $sizes = Size::all();

        return view('size.index', [
            'sizes' => $sizes,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('size.create');
    }

    public function store(SizeStoreRequest $request): Response
    {
        $size = Size::create($request->validated());

        $request->session()->flash('size.id', $size->id);

        return redirect()->route('sizes.index');
    }

    public function show(Request $request, Size $size): Response
    {
        return view('size.show', [
            'size' => $size,
        ]);
    }

    public function edit(Request $request, Size $size): Response
    {
        return view('size.edit', [
            'size' => $size,
        ]);
    }

    public function update(SizeUpdateRequest $request, Size $size): Response
    {
        $size->update($request->validated());

        $request->session()->flash('size.id', $size->id);

        return redirect()->route('sizes.index');
    }

    public function destroy(Request $request, Size $size): Response
    {
        $size->delete();

        return redirect()->route('sizes.index');
    }
}
