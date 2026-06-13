<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['category', 'specifications'])->findOrFail($id);
        $isWishlisted = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->where('product_id', $id)->exists()
            : false;

        return view('product-detail', compact('product', 'isWishlisted'));
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('nosaukums')) {
            $query->where('name', 'like', '%'.$request->input('nosaukums').'%');
        }

        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->input('categories'));
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

        switch ($request->input('sort')) {
            case 'price-low-high':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high-low':
                $query->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
        }

        $products = $query->get();
        $categories = Category::orderBy('category')->get();
        $maxPrice = Product::max('price') ?? 1000;

        return view('products', compact('products', 'categories', 'maxPrice'));
    }
}
