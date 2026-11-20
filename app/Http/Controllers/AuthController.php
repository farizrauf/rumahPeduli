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

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $berhasil = Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 'aktif',
        ], $request->boolean('remember'));

        if (! $berhasil) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])
                ->withInput($request->only('email', 'remember'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route($this->routeDashboard($request->user())));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|max:150|unique:users,email',
            'password'    => 'required|confirmed|min:8',
            'role'        => 'required|in:donatur,panti',
            'terms'       => 'accepted',
            'nama_panti'  => 'required_if:role,panti|nullable|string|max:150',
            'alamat'      => 'required_if:role,panti|nullable|string|max:3000',
            'kota'        => 'required_if:role,panti|nullable|string|max:100',
            'jumlah_anak' => 'nullable|integer|min:0',
            'deskripsi'   => 'nullable|string|max:5000',
            'dokumen'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $dokumen = null;
        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen')->store('dokumen-panti', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'aktif',
        ]);

        if ($request->role === 'panti') {
            $user->panti()->create([
                'nama'              => $request->nama_panti,
                'alamat'            => $request->alamat,
                'kota'              => $request->kota,
                'jumlah_anak'       => $request->jumlah_anak ?? 0,
                'deskripsi'         => $request->deskripsi,
                'dokumen'           => $dokumen,
                'status_verifikasi' => 'pending',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $pesan = $request->role === 'panti'
            ? 'Akun dan pengajuan panti berhasil dibuat. Menunggu verifikasi admin.'
            : 'Akun berhasil dibuat. Selamat datang di Rumah Peduli!';

        return redirect()->route('dashboard')->with('success', $pesan);
    }

    public function dashboard(Request $request)
    {
        return redirect()->route($this->routeDashboard($request->user()));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar dari akun.');
    }

    // ─── Private Helper ──────────────────────────────────────────

    private function routeDashboard(User $user): string
    {
        if ($user->role === 'admin') return 'admin.dashboard';
        if ($user->role === 'panti') return 'panti.dashboard';
        return 'donatur.dashboard';
    }
}
