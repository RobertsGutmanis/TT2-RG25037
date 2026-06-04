<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('userData');
        $orders = collect();

        return view('account', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:16',
            'last_name'  => 'required|string|max:16',
            'phone_num'  => 'nullable|integer',
            'phone_code' => 'nullable|integer',
            'country'    => 'nullable|string|max:32',
            'address'    => 'nullable|string|max:64',
            'city'       => 'nullable|string|max:16',
            'zip'        => 'nullable|string|max:7',
        ]);

        Auth::user()->userData()->updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['name', 'last_name', 'phone_num', 'phone_code', 'country', 'address', 'city', 'zip'])
        );

        return back()->with('success', 'Profils atjaunināts!');
    }
}