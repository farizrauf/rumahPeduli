@extends('layouts.app')
@section('title', 'Buat Akun')
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

            <h1 class="auth-title">Buat Akun Baru</h1>
            <p class="auth-subtitle">Bergabung dan mulai berbagi kebaikan bersama</p>

            @if ($errors->any())
                <div class="flash flash-error" style="margin-bottom:18px">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="auth-form-group">
                    <label>Nama Lengkap</label>
                    <input name="name" value="{{ old('name') }}" placeholder="Nama sesuai identitas" maxlength="100" required>
                </div>

                <div class="auth-form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required>
                </div>

                <div class="auth-form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                </div>

                <div class="auth-form-group">
                    <label>Bergabung sebagai</label>
                    <select name="role" id="account-role">
                        <option value="donatur" @selected(old('role','donatur') === 'donatur')>Donatur</option>
                        <option value="panti" @selected(old('role') === 'panti')>Pengelola Panti</option>
                    </select>
                </div>

                {{-- Panti fields (hidden unless role = panti) --}}
                <div class="panti-fields" id="panti-fields">
                    <div class="panti-fields-title">Profil Panti</div>
                    <div class="auth-form-group">
                        <label>Nama Panti</label>
                        <input name="nama_panti" value="{{ old('nama_panti') }}" maxlength="150" placeholder="Nama resmi panti">
                    </div>
                    <div class="auth-form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="2">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="auth-form-group">
                        <label>Kota</label>
                        <input name="kota" value="{{ old('kota') }}" maxlength="100">
                    </div>
                    <div class="auth-form-group">
                        <label>Jumlah Anak Binaan</label>
                        <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak', 0) }}" min="0">
                    </div>
                    <div class="auth-form-group">
                        <label>Deskripsi Panti</label>
                        <textarea name="deskripsi" rows="2" placeholder="Ceritakan visi dan misi panti...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="auth-form-group">
                        <label>Dokumen Legalitas <span style="font-weight:400;text-transform:none;color:var(--muted)">(PDF/JPG, maks 5MB)</span></label>
                        <input type="file" name="dokumen" accept=".pdf,image/*">
                    </div>
                </div>

                <div class="auth-form-group">
                    <label>Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang kata sandi" autocomplete="new-password" required>
                </div>

                <label class="auth-checkbox" style="margin-bottom:22px">
                    <input type="checkbox" name="terms" value="1" required>
                    Saya setuju dengan <a href="#" style="color:var(--teal);font-weight:700;text-decoration:underline">Syarat &amp; Ketentuan</a>
                </label>

                <button class="auth-submit-btn" type="submit">Daftar</button>
            </form>

            <div class="auth-switch">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
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

@push('scripts')
<script>
const roleSelect = document.getElementById('account-role');
const pantiFields = document.getElementById('panti-fields');
if (roleSelect && pantiFields) {
    const toggle = () => {
        const show = roleSelect.value === 'panti';
        pantiFields.classList.toggle('visible', show);
        pantiFields.querySelectorAll('input,textarea').forEach(el => {
            el.required = show && ['nama_panti','alamat','kota'].includes(el.name);
        });
    };
    roleSelect.addEventListener('change', toggle);
    toggle();
}
</script>
@endpush

@endsection
