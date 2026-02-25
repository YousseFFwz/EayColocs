<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        session(['user_id' => $user->id]);

        return redirect('/dashboard');
    }


    public function showLogin()
    {
        return view('auth.login');
    }


     public function login(Request $request)
    {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Invalid credentials');
    }

    if ($user->is_banned) {
        return back()->with('error', 'You are banned');
    }

    session(['user_id' => $user->id]);

    
    if ($user->role === 'admin') {
        return redirect('/admin');
    }

    return redirect('/dashboard');
   }


   public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
