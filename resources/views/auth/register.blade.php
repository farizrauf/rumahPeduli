@extends('layouts.app')
@section('title', 'Buat Akun')
@section('content')

<section class="auth-container-split">
    <div class="auth-left-panel register-scroll">
        <div class="auth-form-wrapper register-form-wrapper">
            <div class="auth-logo-split register-logo">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
                <span>Rumah Peduli</span>
            </div>

            <h1 class="auth-title-split register-title">Buat akun baru</h1>
            <p class="auth-subtitle-split register-subtitle">Bergabung dan mulai berbagi kebaikan bersama.</p>

            @if($errors->any())
                <div class="auth-alert-split error" role="alert" aria-live="polite">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" class="auth-form-split register-form">
                @csrf

                <div class="form-field-split">
                    <label for="register-name">Nama lengkap</label>
                    <input id="register-name" name="name" value="{{ old('name') }}" placeholder="Nama sesuai identitas" maxlength="100" autocomplete="name" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" aria-describedby="@error('name') register-name-error @enderror" required>
                    @error('name')<small class="field-error" id="register-name-error">{{ $message }}</small>@enderror
                </div>

                <div class="form-field-split">
                    <label for="register-email">Email</label>
                    <input id="register-email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" maxlength="150" autocomplete="email" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="@error('email') register-email-error @enderror" required>
                    @error('email')<small class="field-error" id="register-email-error">{{ $message }}</small>@enderror
                </div>

                <div class="form-field-split">
                    <label for="password-reg">Kata sandi</label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="password-reg" placeholder="Minimal 8 karakter" autocomplete="new-password" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" aria-describedby="@error('password') register-password-error @enderror" required>
                        <button type="button" class="password-toggle" data-password-toggle="password-reg" aria-label="Tampilkan kata sandi">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<small class="field-error" id="register-password-error">{{ $message }}</small>@enderror
                </div>

                <div class="form-field-split">
                    <label for="role-select">Bergabung sebagai</label>
                    <select name="role" id="role-select" required>
                        <option value="donatur" @selected(old('role', 'donatur') === 'donatur')>Donatur</option>
                        <option value="panti" @selected(old('role') === 'panti')>Pengelola panti</option>
                    </select>
                </div>

                <fieldset class="panti-fields-split" id="panti-fields-box" aria-labelledby="panti-fields-title" @if(old('role', 'donatur') !== 'panti') hidden @endif>
                    <legend id="panti-fields-title">Profil panti</legend>
                    <p class="panti-fields-hint">Lengkapi profil untuk mengajukan verifikasi panti.</p>

                    <div class="form-field-split">
                        <label for="register-nama-panti">Nama panti</label>
                        <input id="register-nama-panti" name="nama_panti" value="{{ old('nama_panti') }}" maxlength="150" placeholder="Nama resmi panti" data-panti-required aria-invalid="{{ $errors->has('nama_panti') ? 'true' : 'false' }}" aria-describedby="@error('nama_panti') register-nama-panti-error @enderror">
                        @error('nama_panti')<small class="field-error" id="register-nama-panti-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-field-split">
                        <label for="register-address">Alamat</label>
                        <textarea id="register-address" name="alamat" rows="3" maxlength="3000" placeholder="Alamat lengkap panti" data-panti-required aria-invalid="{{ $errors->has('alamat') ? 'true' : 'false' }}" aria-describedby="@error('alamat') register-address-error @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')<small class="field-error" id="register-address-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="register-grid-two">
                        <div class="form-field-split">
                            <label for="register-city">Kota</label>
                            <input id="register-city" name="kota" value="{{ old('kota') }}" maxlength="100" placeholder="Kota" data-panti-required aria-invalid="{{ $errors->has('kota') ? 'true' : 'false' }}" aria-describedby="@error('kota') register-city-error @enderror">
                            @error('kota')<small class="field-error" id="register-city-error">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-field-split">
                            <label for="register-children">Jumlah anak binaan</label>
                            <input id="register-children" type="number" name="jumlah_anak" value="{{ old('jumlah_anak', 0) }}" min="0" inputmode="numeric" aria-invalid="{{ $errors->has('jumlah_anak') ? 'true' : 'false' }}" aria-describedby="@error('jumlah_anak') register-children-error @enderror">
                            @error('jumlah_anak')<small class="field-error" id="register-children-error">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="form-field-split">
                        <label for="register-description">Deskripsi <span class="optional">(opsional)</span></label>
                        <textarea id="register-description" name="deskripsi" rows="3" maxlength="5000" placeholder="Ceritakan visi dan kegiatan panti">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<small class="field-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="form-field-split">
                        <label for="register-document">Dokumen legalitas <span class="optional">(opsional · PDF/JPG/PNG, maks. 5 MB)</span></label>
                        <input id="register-document" type="file" name="dokumen" accept=".pdf,image/jpeg,image/png" aria-invalid="{{ $errors->has('dokumen') ? 'true' : 'false' }}" aria-describedby="@error('dokumen') register-document-error @enderror">
                        @error('dokumen')<small class="field-error" id="register-document-error">{{ $message }}</small>@enderror
                    </div>
                </fieldset>

                <div class="form-field-split">
                    <label for="password-confirmation">Ulangi kata sandi</label>
                    <input type="password" name="password_confirmation" id="password-confirmation" placeholder="Ketik ulang kata sandi" autocomplete="new-password" required>
                </div>

                <div>
                    <label class="checkbox-split" for="register-terms">
                        <input id="register-terms" type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                        <span>Saya setuju dengan <a href="{{ route('terms') }}" target="_blank" rel="noopener">Syarat &amp; Ketentuan</a></span>
                    </label>
                    @error('terms')<small class="field-error">{{ $message }}</small>@enderror
                </div>

                <button type="submit" class="btn-submit-split">Daftar</button>
            </form>

            <div class="auth-footer-split register-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>

    <div class="auth-right-panel">
        <img src="{{ asset('assets/figma/auth-bg.jpg') }}" alt="" class="auth-panel-photo">
        <div class="auth-overlay-content">
            <div class="overlay-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <h2 class="overlay-title">Jembatan Kebaikan</h2>
            <p class="overlay-text">Menghubungkan hati yang ingin berbagi dengan mereka yang membutuhkan.</p>
            <div class="overlay-features">
                <div class="feature-item"><span aria-hidden="true">✓</span><span>Terverifikasi</span></div>
                <div class="feature-item"><span aria-hidden="true">✓</span><span>Transparan</span></div>
                <div class="feature-item"><span aria-hidden="true">✓</span><span>Berdampak nyata</span></div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    const registrationRole = document.getElementById('role-select');
    const registrationPantiFields = document.getElementById('panti-fields-box');

    function updateRegistrationPantiFields() {
        const isPanti = registrationRole.value === 'panti';
        registrationPantiFields.hidden = !isPanti;
        registrationPantiFields.querySelectorAll('[data-panti-required]').forEach((input) => {
            input.required = isPanti;
        });
    }

    registrationRole.addEventListener('change', updateRegistrationPantiFields);
    updateRegistrationPantiFields();

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
