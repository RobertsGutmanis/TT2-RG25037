<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('userData');
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->orderByDesc('id')
            ->get();

        return view('account', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:16',
            'last_name' => 'required|string|max:16',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone_num' => 'nullable|integer',
            'phone_code' => 'nullable|integer',
            'country' => 'nullable|string|max:32',
            'address' => 'nullable|string|max:64',
            'city' => 'nullable|string|max:16',
            'zip' => 'nullable|string|max:7',
        ]);

        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->save();
        }

        $user->userData()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['name', 'last_name', 'phone_num', 'phone_code', 'country', 'address', 'city', 'zip'])
        );

        AuditLog::log('profile_update', []);

        return back()->with('success', 'Profile updated!');
    }
}
