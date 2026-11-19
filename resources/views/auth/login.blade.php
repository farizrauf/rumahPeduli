@extends('layouts.app')
@section('title', 'Masuk')
@section('content')

<section class="auth-container-split">
    
    {{-- LEFT PANEL: FORM --}}
    <div class="auth-left-panel">
        <div class="auth-form-wrapper">
            
            {{-- Logo --}}
            <div class="auth-logo-split">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
                <span>Rumah Peduli</span>
            </div>

            {{-- Title --}}
            <h1 class="auth-title-split">Selamat Datang Kembali</h1>
            <p class="auth-subtitle-split">Masuk untuk melanjutkan kebaikan bersama</p>

            <!-- {{-- Divider --}}
            <div class="auth-or-divider">
                <span>Atau</span>
            </div> -->

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="auth-alert-split error">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="auth-alert-split success">{{ session('success') }}</div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.store') }}" class="auth-form-split">
                @csrf

                <div class="form-field-split">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                <div class="form-field-split">
                    <label>Kata Sandi</label>
                    <div class="input-with-toggle">
                        <input
                            type="password"
                            name="password"
                            id="password-field"
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePass('password-field')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-row-split">
                    <label class="checkbox-split">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="link-forgot-split">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn-submit-split">Masuk</button>
            </form>

            {{-- Footer Link --}}
            <div class="auth-footer-split">
                Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
            </div>

        </div>
    </div>

    {{-- RIGHT PANEL: PHOTO WITH OVERLAY --}}
    <div class="auth-right-panel">
        <img src="{{ asset('assets/figma/auth-bg.jpg') }}" alt="" class="auth-panel-photo">
        <div class="auth-overlay-content">
            <div class="overlay-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <h2 class="overlay-title">Jembatan Kebaikan</h2>
            <p class="overlay-text">Menghubungkan hati yang ingin berbagi dengan mereka yang membutuhkan</p>
            <div class="overlay-features">
                <div class="feature-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Terverifikasi</span>
                </div>
                <div class="feature-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Transparan</span>
                </div>
                <div class="feature-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>Berdampak Nyata</span>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
function togglePass(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

@endsection
