@extends('layouts.app')
@section('title', 'Masuk')
@section('content')

<section class="auth-screen">
    <div class="auth-panel">

        {{-- ══════ LEFT: FORM ══════ --}}
        <div class="auth-form-wrap">

            {{-- Back to Home Button --}}
            <a href="{{ route('home') }}" class="auth-back-btn">
                Kembali ke Beranda
            </a>

            {{-- Brand --}}
            <div class="auth-brand">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
                <span class="auth-brand-name">Rumah Peduli.</span>
            </div>

            <h1 class="auth-title">Selamat Datang Kembali</h1>
            <p class="auth-subtitle">Masuk untuk melanjutkan kebaikan bersama</p>

            @if ($errors->any())
                <div class="flash flash-error" style="margin-bottom:18px">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="flash flash-success" style="margin-bottom:18px">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="auth-form-group">
                    <label for="login-email">Email</label>
                    <input
                        id="login-email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                <div class="auth-form-group">
                    <label for="login-password">Kata Sandi</label>
                    <input
                        id="login-password"
                        type="password"
                        name="password"
                        placeholder="••••••••••"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="auth-form-row">
                    <label class="auth-checkbox">
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>
                    <a class="auth-forgot" href="#">Lupa kata sandi?</a>
                </div>

                <button class="auth-submit-btn" type="submit">Masuk</button>
            </form>

            <div class="auth-switch">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </div>

        </div>

        {{-- ══════ RIGHT: PHOTO ══════ --}}
        <div class="auth-photo-panel">
            <div class="auth-photo-bg" style="background-image: url('{{ asset('assets/figma/panti-1.jpeg') }}');"></div>
            <div class="auth-photo-overlay"></div>

            <div class="auth-photo-caption">
                <p>Jembatan kebaikan antara donatur dan panti terpercaya.</p>
            </div>

            <div class="auth-photo-trust">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Terverifikasi &nbsp;·&nbsp; Transparan &nbsp;·&nbsp; Berdampak nyata
            </div>
        </div>

    </div>
</section>

@endsection
