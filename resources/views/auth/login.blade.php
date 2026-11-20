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

            @if ($errors->any())
                <div class="auth-alert-split error" role="alert" aria-live="polite">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="auth-alert-split success" role="status">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="auth-form-split">
                @csrf

                <div class="form-field-split">
                    <label for="login-email">Email</label>
                    <input
                        id="login-email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        autocomplete="email"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        aria-describedby="@error('email') login-email-error @enderror"
                        required
                        autofocus
                    >
                    @error('email')<small class="field-error" id="login-email-error">{{ $message }}</small>@enderror
                </div>

                <div class="form-field-split">
                    <label for="password-field">Kata sandi</label>
                    <div class="input-with-toggle">
                        <input
                            type="password"
                            name="password"
                            id="password-field"
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            aria-describedby="@error('password') login-password-error @enderror"
                            required
                        >
                        <button type="button" class="password-toggle" data-password-toggle="password-field" aria-label="Tampilkan kata sandi">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<small class="field-error" id="login-password-error">{{ $message }}</small>@enderror
                </div>

                <div class="form-row-split">
                    <label class="checkbox-split">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        <span>Ingat saya</span>
                    </label>
                    <a href="mailto:halo@rumahpeduli.id?subject=Bantuan%20akses%20akun" class="link-forgot-split">Butuh bantuan masuk?</a>
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

@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const isVisible = input.type === 'password';
            input.type = isVisible ? 'text' : 'password';
            button.setAttribute('aria-label', isVisible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    });
</script>
@endpush
