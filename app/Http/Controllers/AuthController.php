<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPage()
    {
        if (auth()->check()) {
            return redirect()->route('account.index');
        }
        return view('login');
    }

    public function registerPage()
    {
        if (auth()->check()) {
            return redirect()->route('account.index');
        }
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'name'      => 'required|string|max:16',
            'last_name' => 'required|string|max:16',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->userData()->create([
            'name'      => $request->name,
            'last_name' => $request->last_name,
        ]);

        $user->assignRole('customer');

        Auth::login($user);

        return redirect()->route('products.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('products.index');
        }

        return back()->withErrors(['email' => 'Nepareizs e-pasts vai parole.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}