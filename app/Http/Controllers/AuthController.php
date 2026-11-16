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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials + ['status' => 'aktif'], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route($this->dashboardRouteName($request->user())));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
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

        DB::transaction(function () use ($request, $validated, &$user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => 'aktif',
            ]);

            if ($validated['role'] === 'panti') {
                $user->panti()->create([
                    'nama' => $validated['nama_panti'],
                    'alamat' => $validated['alamat'],
                    'kota' => $validated['kota'],
                    'jumlah_anak' => $validated['jumlah_anak'] ?? 0,
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'dokumen' => $request->file('dokumen')?->store('dokumen-panti', 'public'),
                    'status_verifikasi' => 'pending',
                ]);
            }
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', $user->role === 'panti'
            ? 'Akun dan pengajuan panti berhasil dibuat. Profil Anda menunggu verifikasi admin.'
            : 'Akun berhasil dibuat. Selamat datang di Rumah Peduli!');
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
        return match ($user->role) {
            'admin' => 'admin.dashboard',
            'panti' => 'panti.dashboard',
            default => 'donatur.dashboard',
        };
    }
}
