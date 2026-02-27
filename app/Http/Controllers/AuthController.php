<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // Panggil Mesin Log

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // LOG: Login Berhasil
            ActivityLog::record(
                'Login Berhasil',
                'Auth',
                'User "' . Auth::user()->username . '" masuk ke sistem.'
            );

            return redirect()->intended('dashboard');
        }

        // LOG: Gagal Login (Sangat Penting untuk Keamanan)
        // Kita tidak pakai Auth::id() karena memang belum login, jadi catat username yang dicoba
        ActivityLog::create([
            'user_id' => 1, // Diarahkan ke Admin sebagai pemberitahuan
            'activity' => 'Gagal Login',
            'model' => 'Auth',
            'description' => 'Percobaan login gagal dengan username: ' . $request->username,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            // LOG: Logout (Catat sebelum session dihancurkan)
            ActivityLog::record(
                'Logout',
                'Auth',
                'User "' . Auth::user()->username . '" keluar dari sistem.'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
