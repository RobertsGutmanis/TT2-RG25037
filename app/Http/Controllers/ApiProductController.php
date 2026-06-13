<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductController extends Controller
{
    // GET /api/products
    // Optional query params: search, category_id, price_min, price_max, on_sale, sort
    public function index(Request $request)
    {
        $query = Product::with(['category', 'specifications']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        if ($request->boolean('on_sale')) {
            $query->whereColumn('price', '<', 'last_price');
        }

        $sortOptions = [
            'price-low-high' => ['price', 'asc'],
            'price-high-low' => ['price', 'desc'],
            'name-asc' => ['name', 'asc'],
            'name-desc' => ['name', 'desc'],
        ];

        [$column, $direction] = $sortOptions[$request->input('sort')] ?? ['id', 'desc'];
        $query->orderBy($column, $direction);

        return response()->json($query->get());
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with(['category', 'specifications'])->findOrFail($id);

        return response()->json($product);
    }
}
