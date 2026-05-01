<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function processLogin(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Kembali ke halaman sebelumnya dengan pesan error khusus untuk modal
        return back()->with('login_error', 'Username atau Password salah!');
    }

    public function showLogin() {
        return view('auth.login'); // Pastikan file view ini ada
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Mengarahkan kembali ke halaman login setelah logout
        return redirect()->route('login'); 
    }
}