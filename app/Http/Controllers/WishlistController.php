<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLog;
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

        $product = Product::find($productId);
        $productName = $product?->name ?? $productId;

        if ($existing) {
            $existing->delete();
            AuditLog::log('wishlist_remove', ['product_id' => $productId, 'product' => $productName]);
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            AuditLog::log('wishlist_add', ['product_id' => $productId, 'product' => $productName]);
        }

        return redirect()->back();
    }
}
