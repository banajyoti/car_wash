<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    public function showForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'car_number' => 'required|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'car_no' => $request->car_number,
            'user_type' => 2,  // set user_type to 2 here
        ]);

        return redirect()->route('login.form')->with('success', 'Registration successful!');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->user_type == 2) {
                return redirect()->route('customer.dashboard');
            } elseif (Auth::user()->user_type == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors(['access' => 'Unauthorized user type']);
            }
        }

        return redirect()->back()->withErrors(['login' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}