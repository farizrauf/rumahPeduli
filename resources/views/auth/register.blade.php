@extends('layouts.app')
@section('title', 'Buat Akun')
@section('content')

<section class="auth-container-split">
    
    {{-- LEFT PANEL: FORM (Scrollable) --}}
    <div class="auth-left-panel register-scroll">
        <div class="auth-form-wrapper">
            
            {{-- Logo --}}
            <div class="auth-logo-split">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
                <span>Rumah Peduli</span>
            </div>

            {{-- Title --}}
            <h1 class="auth-title-split">Buat Akun Baru</h1>
            <p class="auth-subtitle-split">Bergabung dan mulai berbagi kebaikan bersama</p>

            {{-- Divider --}}
            <!-- <div class="auth-or-divider">
                <span>Atau</span>
            </div> -->

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="auth-alert-split error">{{ $errors->first() }}</div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" class="auth-form-split">
                @csrf

                <div class="form-field-split">
                    <label>Nama Lengkap</label>
                    <input name="name" value="{{ old('name') }}" placeholder="Nama sesuai identitas" maxlength="100" required>
                </div>

                <div class="form-field-split">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required>
                </div>

                <div class="form-field-split">
                    <label>Kata Sandi</label>
                    <div class="input-with-toggle">
                        <input type="password" name="password" id="password-reg" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" onclick="togglePass('password-reg')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-field-split">
                    <label>Bergabung sebagai</label>
                    <select name="role" id="role-select">
                        <option value="donatur" @selected(old('role','donatur') === 'donatur')>Donatur</option>
                        <option value="panti" @selected(old('role') === 'panti')>Pengelola Panti</option>
                    </select>
                </div>

                {{-- Panti Fields (Conditional) --}}
                <div class="panti-fields-split" id="panti-fields-box">
                    <div class="panti-title">Profil Panti</div>
                    
                    <div class="form-field-split">
                        <label>Nama Panti</label>
                        <input name="nama_panti" value="{{ old('nama_panti') }}" maxlength="150" placeholder="Nama resmi panti">
                    </div>
                    
                    <div class="form-field-split">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="2">{{ old('alamat') }}</textarea>
                    </div>
                    
                    <div class="form-field-split">
                        <label>Kota</label>
                        <input name="kota" value="{{ old('kota') }}" maxlength="100">
                    </div>
                    
                    <div class="form-field-split">
                        <label>Jumlah Anak Binaan</label>
                        <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', 0) }}" min="0">
                    </div>
                    
                    <div class="form-field-split">
                        <label>Deskripsi Panti</label>
                        <textarea name="deskripsi" rows="2" placeholder="Ceritakan visi dan misi panti...">{{ old('deskripsi') }}</textarea>
                    </div>
                    
                    <div class="form-field-split">
                        <label>Dokumen Legalitas <small style="font-weight:400;color:#9ca3af">(PDF/JPG, maks 5MB)</small></label>
                        <input type="file" name="dokumen" accept=".pdf,image/*">
                    </div>
                </div>

                <div class="form-field-split">
                    <label>Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang kata sandi" autocomplete="new-password" required>
                </div>

                <label class="checkbox-split" style="margin: 12px 0 20px;">
                    <input type="checkbox" name="terms" value="1" required>
                    <span>Saya setuju dengan <a href="#" style="color:var(--green);font-weight:600;text-decoration:underline">Syarat & Ketentuan</a></span>
                </label>

                <button type="submit" class="btn-submit-split">Daftar</button>
            </form>

            {{-- Footer Link --}}
            <div class="auth-footer-split">
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
