<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name'      => 'required|string',
            'phone'     => 'required|string',
            'user_type' => 'required|string',
        ]);

        $user = User::where('name', $credentials['name'])
            ->where('phone', $credentials['phone'])
            ->where('user_type', $credentials['user_type'])
            ->first();
        
        if ($user) {
            Auth::login($user);
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['login_error' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form')->with('success', 'Logged out successfully.');
    }
}
