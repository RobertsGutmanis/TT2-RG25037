<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLog;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('cart', compact('cart', 'total'));
    }

    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id'        => $product->id,
                'name'      => $product->name,
                'price'     => $product->price,
                'image_url' => $product->image_url,
                'quantity'  => 1,
            ];
        }

        AuditLog::log('cart_add', ['product_id' => $id, 'product' => $product->name]);
        session(['cart' => $cart]);
        return redirect()->back();
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        $name = $cart[$id]['name'] ?? $id;
        unset($cart[$id]);
        AuditLog::log('cart_remove', ['product_id' => $id, 'product' => $name]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function update(Request $request, $id)
    {
        $cart = session('cart', []);
        $qty = (int) $request->input('quantity', 1);

        if (isset($cart[$id])) {
            if ($qty < 1) {
                AuditLog::log('cart_remove', ['product_id' => $id, 'product' => $cart[$id]['name']]);
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
                AuditLog::log('cart_update', ['product_id' => $id, 'product' => $cart[$id]['name'], 'quantity' => $qty]);
            }
        }

        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function clear()
    {
        AuditLog::log('cart_clear', []);
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
