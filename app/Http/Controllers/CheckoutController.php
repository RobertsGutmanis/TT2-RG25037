<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLog;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'eur',
                    'product_data' => ['name' => $item['name']],
                    'unit_amount'  => (int) round($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('cart.index'),
        ]);

        session(['pending_stripe_session' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId || session('pending_stripe_session') !== $sessionId) {
            return redirect()->route('cart.index');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $stripeSession = StripeSession::retrieve($sessionId);

        if ($stripeSession->payment_status !== 'paid') {
            return redirect()->route('cart.index')
                ->with('error', 'Payment was not completed.');
        }

        $cart  = session('cart', []);
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

        AuditLog::log('checkout', ['order_id' => $order->id, 'total' => number_format($total, 2), 'items' => count($cart)]);
        session()->forget(['cart', 'pending_stripe_session']);

        return redirect()->route('account.index')->with('success', 'Order placed successfully!');
    }
}
