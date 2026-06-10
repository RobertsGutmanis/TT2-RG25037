<?php

namespace App\Http\Controllers;

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

        session(['cart' => $cart]);
        return redirect()->back();
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function update(Request $request, $id)
    {
        $cart = session('cart', []);
        $qty = (int) $request->input('quantity', 1);

        if (isset($cart[$id])) {
            if ($qty < 1) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
            }
        }

        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
