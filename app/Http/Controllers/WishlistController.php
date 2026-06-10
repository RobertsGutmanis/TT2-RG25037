<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::with('product.category')
            ->where('user_id', Auth::id())
            ->get();

        return view('wishlist', compact('items'));
    }

    public function toggle($productId)
    {
        $userId = Auth::id();
        $existing = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
        }

        return redirect()->back();
    }
}
