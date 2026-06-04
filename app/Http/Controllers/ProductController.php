<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('nosaukums')) {
            $query->where('name', 'like', '%' . $request->input('nosaukums') . '%');
        }

         switch ($request->input('sort')) {
        case 'price-low-high':
            $query->orderBy('price', 'asc');
            break;
        case 'price-high-low':
            $query->orderBy('price', 'desc');
            break;
        case 'name-low-high':
            $query->orderBy('name', 'asc');
            break;
        case 'name-high-low':
            $query->orderBy('name', 'desc');
            break;
        }

        $products = $query->get();

        return view('products', compact('products'));
    }
}
