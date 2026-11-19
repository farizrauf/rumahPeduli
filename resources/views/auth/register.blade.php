@extends('layouts.app')
@section('title', 'Buat Akun')
@section('content')

<section class="auth-container-split">
    
    {{-- LEFT PANEL: FORM (Compact, no scroll needed) --}}
    <div class="auth-left-panel register-scroll" style="padding:25px 30px;">
        <div class="auth-form-wrapper" style="max-width:400px;">
            
            {{-- Logo --}}
            <div class="auth-logo-split" style="margin-bottom:16px;">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli" style="height:36px;">
                <span style="font-size:15px;">Rumah Peduli</span>
            </div>

            {{-- Title --}}
            <h1 class="auth-title-split" style="font-size:24px;margin-bottom:4px;">Buat Akun Baru</h1>
            <p class="auth-subtitle-split" style="font-size:13px;margin-bottom:16px;">Bergabung dan mulai berbagi kebaikan bersama</p>

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="auth-alert-split error" style="margin-bottom:14px;padding:10px 12px;font-size:13px;">{{ $errors->first() }}</div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" class="auth-form-split" style="gap:12px;">
                @csrf

                <div class="form-field-split" style="gap:5px;">
                    <label style="font-size:12px;">Nama Lengkap</label>
                    <input name="name" value="{{ old('name') }}" placeholder="Nama sesuai identitas" maxlength="100" style="padding:9px 11px;font-size:13px;" required>
                </div>

                <div class="form-field-split" style="gap:5px;">
                    <label style="font-size:12px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" style="padding:9px 11px;font-size:13px;" required>
                </div>

                <div class="form-field-split" style="gap:5px;">
                    <label style="font-size:12px;">Kata Sandi</label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="password-reg" placeholder="Minimal 8 karakter" autocomplete="new-password" style="padding:9px 11px;font-size:13px;" required>
                        <button type="button" class="password-toggle" onclick="togglePass('password-reg')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-field-split" style="gap:5px;">
                    <label style="font-size:12px;">Bergabung sebagai</label>
                    <select name="role" id="role-select" style="padding:9px 11px;font-size:13px;">
                        <option value="donatur" @selected(old('role','donatur') === 'donatur')>Donatur</option>
                        <option value="panti" @selected(old('role') === 'panti')>Pengelola Panti</option>
                    </select>
                </div>

                {{-- Panti Fields (Conditional) --}}
                <div class="panti-fields-split" id="panti-fields-box" style="gap:12px;padding:14px;margin-top:4px;">
                    <div class="panti-title" style="font-size:12px;margin-bottom:2px;">Profil Panti</div>
                    
                    <div class="form-field-split" style="gap:5px;">
                        <label style="font-size:12px;">Nama Panti</label>
                        <input name="nama_panti" value="{{ old('nama_panti') }}" maxlength="150" placeholder="Nama resmi panti" style="padding:9px 11px;font-size:13px;">
                    </div>
                    
                    <div class="form-field-split" style="gap:5px;">
                        <label style="font-size:12px;">Alamat</label>
                        <textarea name="alamat" rows="1" style="padding:9px 11px;font-size:13px;min-height:38px;">{{ old('alamat') }}</textarea>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-field-split" style="gap:5px;">
                            <label style="font-size:12px;">Kota</label>
                            <input name="kota" value="{{ old('kota') }}" maxlength="100" style="padding:9px 11px;font-size:13px;">
                        </div>
                        
                        <div class="form-field-split" style="gap:5px;">
                            <label style="font-size:12px;">Jumlah Anak</label>
                            <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', 0) }}" min="0" style="padding:9px 11px;font-size:13px;">
                        </div>
                    </div>
                    
                    <div class="form-field-split" style="gap:5px;">
                        <label style="font-size:12px;">Deskripsi Panti</label>
                        <textarea name="deskripsi" rows="1" placeholder="Visi dan misi panti..." style="padding:9px 11px;font-size:13px;min-height:38px;">{{ old('deskripsi') }}</textarea>
                    </div>
                    
                    <div class="form-field-split" style="gap:5px;">
                        <label style="font-size:12px;">Dokumen Legalitas <small style="font-weight:400;color:#9ca3af;font-size:11px;">(PDF/JPG, maks 5MB)</small></label>
                        <input type="file" name="dokumen" accept=".pdf,image/*" style="padding:7px 9px;font-size:12px;">
                    </div>
                </div>

                <div class="form-field-split" style="gap:5px;">
                    <label style="font-size:12px;">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang kata sandi" autocomplete="new-password" style="padding:9px 11px;font-size:13px;" required>
                </div>

                <label class="checkbox-split" style="margin:8px 0 12px;font-size:12px;">
                    <input type="checkbox" name="terms" value="1" required>
                    <span>Saya setuju dengan <a href="{{ route('terms') }}" target="_blank" style="color:var(--green);font-weight:600;text-decoration:underline">Syarat & Ketentuan</a></span>
                </label>

                <button type="submit" class="btn-submit-split" style="padding:11px 20px;font-size:14px;">Daftar</button>
            </form>

            {{-- Footer Link --}}
            <div class="auth-footer-split" style="margin-top:14px;font-size:13px;">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
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

// Toggle panti fields
document.getElementById('role-select').addEventListener('change', function() {
    const box = document.getElementById('panti-fields-box');
    box.style.display = this.value === 'panti' ? 'flex' : 'none';
});

// Initial check
if (document.getElementById('role-select').value !== 'panti') {
    document.getElementById('panti-fields-box').style.display = 'none';
}
</script>

@endsection
