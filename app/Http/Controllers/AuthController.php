<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [], ['email' => 'E-posta', 'password' => 'Şifre']);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('account.index'));
        }

        return back()->withInput()->with('error', 'E-posta veya şifre hatalı.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
        ], [], [
            'name' => 'Ad Soyad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'password' => 'Şifre',
        ]);

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('account.index')->with('success', 'Hesabınız oluşturuldu.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
