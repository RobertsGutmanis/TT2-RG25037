<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $userData = Auth::user()->userData;
        $missing = !$userData
            || !$userData->phone_num
            || !$userData->phone_code
            || !$userData->country
            || !$userData->address
            || !$userData->city
            || !$userData->zip;

        if ($missing) {
            return redirect()->route('account.index')
                ->with('error', 'Please complete your profile (phone number, country, address, city, and post index) before placing an order.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        $order = Order::create([
            'user_id'         => Auth::id(),
            'tracking_number' => random_int(10000000, 99999999),
            'created_at'      => now()->toDateString(),
            'delivered_at'    => null,
            'sum'             => (int) round($total * 100),
            'total'           => $total,
            'delivery_method' => 'standard',
            'status'          => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('account.index')->with('success', 'Order placed successfully!');
    }
}
