<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $products   = Product::with(['category', 'specifications'])->get();
        $categories = Category::all();

        return view('admin', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $request->validate([
            'name'         => 'required|string|max:32',
            'manufacturer' => 'required|string|max:64',
            'description'  => 'required|string|max:32',
            'price'        => 'required|numeric',
            'last_price'   => 'required|numeric',
            'image_url'    => 'required|string',
            'category_id'  => 'required|exists:categories,id',
        ]);

        $product = Product::create($request->only([
            'name', 'manufacturer', 'description',
            'price', 'last_price', 'image_url', 'category_id'
        ]));

        if ($request->has('specs')) {
            foreach ($request->specs as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $product->specifications()->create($spec);
                }
            }
        }

        return redirect()->route('admin.index')->with('success', 'Produkts pievienots!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        Product::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Produkts dzēsts!');
    }

    public function editSpecs($id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $product = Product::with('specifications')->findOrFail($id);
        $categories = Category::all();
        return view('admin-specs', compact('product', 'categories'));
    }

    public function updateSpecs(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $product = Product::findOrFail($id);
        $product->specifications()->delete();

        if ($request->has('specs')) {
            foreach ($request->specs as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $product->specifications()->create($spec);
                }
            }
        }

        return redirect()->route('admin.index')->with('success', 'Specifikācijas atjaunotas!');
    }
}