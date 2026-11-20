@extends('layouts.dashboard')
@section('title', 'Ruang Pengelola Panti')
@section('section-name', 'Ringkasan')

@section('dashboard-content')

{{-- HEADER --}}
<div class="dashboard-heading">
    <div>
        <span class="eyebrow">RUANG PENGELOLA PANTI</span>
        <h1>{{ $panti->nama }}</h1>
        <p>{{ $panti->kota }} · Kelola profil, kebutuhan dan aktivitas panti.</p>
    </div>
    <span class="status-badge status-{{ $panti->status_verifikasi }}">
        {{ ucfirst($panti->status_verifikasi) }}
    </span>
</div>

{{-- VERIFICATION NOTICE --}}
@if($panti->status_verifikasi !== 'verified')
<div class="notice-card">
    <strong>
        Profil panti {{ $panti->status_verifikasi === 'pending' ? 'sedang ditinjau' : 'perlu diperbaiki' }}.
    </strong>
    <p>
        {{ $panti->catatan_admin ?? 'Lengkapi profil dan dokumen legalitas. Setelah disetujui, panti dan kampanye akan tampil untuk publik.' }}
    </p>
</div>
@endif

{{-- METRICS --}}
<div class="metric-grid">
    <article class="metric-card">
        <span>Kebutuhan aktif</span>
        <strong>{{ $panti->needs->where('status','dibutuhkan')->count() }}</strong>
        <small>Barang yang masih diperlukan</small>
    </article>
    
    <article class="metric-card metric-green">
        <span>Kampanye terdaftar</span>
        <strong>{{ $panti->campaigns->count() }}</strong>
        <small>{{ $panti->campaigns->where('status','aktif')->count() }} kampanye aktif</small>
    </article>
    
    <article class="metric-card">
        <span>Pengeluaran diverifikasi</span>
        <strong>Rp {{ number_format($panti->expenses->where('status','verified')->sum('nominal'),0,',','.') }}</strong>
        <small>Disertai bukti penyaluran</small>
    </article>
</div>

{{-- PROFILE FORM --}}
<div class="section-heading table-heading">
    <div>
        <span class="eyebrow">PROFIL TERDAFTAR</span>
        <h2>Informasi panti</h2>
    </div>
</div>

<form class="profile-form panti-profile-form" method="POST" action="{{ route('panti.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="form-grid">
        <label>
            Nama panti
            <input name="nama" value="{{ old('nama',$panti->nama) }}" required>
        </label>
        
        <label>
            Kota
            <input name="kota" value="{{ old('kota',$panti->kota) }}" required>
        </label>
        
        <label class="span-two">
            Alamat
            <textarea name="alamat" rows="2" required>{{ old('alamat',$panti->alamat) }}</textarea>
        </label>
        
        <label>
            Jumlah anak binaan
            <input type="number" name="jumlah_anak" value="{{ old('jumlah_anak',$panti->jumlah_anak) }}" min="0" required>
        </label>
        
        <label>
            Foto panti
            @if($panti->foto)
                <span class="optional">Foto saat ini: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($panti->foto) }}" target="_blank" rel="noopener">Lihat foto</a>. Unggah file baru untuk menggantinya.</span>
            @else
                <span class="optional">Opsional · maks. 5 MB</span>
            @endif
            <input type="file" name="foto" accept="image/*">
        </label>
        
        <label class="span-two">
            Deskripsi
            <textarea name="deskripsi" rows="3">{{ old('deskripsi',$panti->deskripsi) }}</textarea>
        </label>
        
        <label class="span-two">
            Dokumen legalitas baru <span class="optional">(PDF/JPG/PNG, maks. 5 MB)</span>
            @if($panti->dokumen)
                <span class="optional">Dokumen saat ini: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($panti->dokumen) }}" target="_blank" rel="noopener">Lihat dokumen</a>. Unggah file baru untuk menggantinya.</span>
            @endif
            <input type="file" name="dokumen" accept=".pdf,image/*">
        </label>
    </div>
    
    <div class="panti-form-actions">
        <button class="button button-primary" type="submit">Simpan profil</button>
    </div>
</form>

@endsection
