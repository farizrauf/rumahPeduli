<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

    public function dashboard(Request $request)
    {
        return redirect()->route($this->dashboardRouteName($request->user()));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $berhasilLogin = Auth::attempt(
            $data + ['status' => 'aktif'],
            $request->boolean('remember')
        );

        if (! $berhasilLogin) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route($this->dashboardRouteName($request->user())));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:donatur,panti'],
            'terms' => ['accepted'],
            'nama_panti' => ['required_if:role,panti', 'nullable', 'string', 'max:150'],
            'alamat' => ['required_if:role,panti', 'nullable', 'string', 'max:3000'],
            'kota' => ['required_if:role,panti', 'nullable', 'string', 'max:100'],
            'jumlah_anak' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $dokumenPanti = null;
        if ($request->hasFile('dokumen')) {
            $dokumenPanti = $request->file('dokumen')->store('dokumen-panti', 'public');
        }

        $user = DB::transaction(function () use ($request, $data, $dokumenPanti) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'status' => 'aktif',
            ]);

            if ($data['role'] === 'panti') {
                $user->panti()->create([
                    'nama' => $data['nama_panti'],
                    'alamat' => $data['alamat'],
                    'kota' => $data['kota'],
                    'jumlah_anak' => $data['jumlah_anak'] ?? 0,
                    'deskripsi' => $data['deskripsi'] ?? null,
                    'dokumen' => $dokumenPanti,
                    'status_verifikasi' => 'pending',
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        $pesan = $user->role === 'panti'
            ? 'Akun dan pengajuan panti berhasil dibuat. Profil Anda menunggu verifikasi admin.'
            : 'Akun berhasil dibuat. Selamat datang di Rumah Peduli!';

        return redirect()->route('dashboard')->with('success', $pesan);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar dari akun.');
    }

    private function dashboardRouteName(User $user): string
    {
        switch ($user->role) {
            case 'admin':
                return 'admin.dashboard';
            case 'panti':
                return 'panti.dashboard';
            default:
                return 'donatur.dashboard';
        }
    }
}
