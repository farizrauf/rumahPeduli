@extends('layouts.app')
@section('title', $kampanye->judul)
@section('content')

@php($raised = $kampanye->dana_terkumpul ?? 0)
@php($progress = $kampanye->target_dana > 0 ? min(100, round($raised / $kampanye->target_dana * 100)) : 0)

{{-- Hero --}}
<section class="panti-detail-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <a href="{{ route('campaigns.index') }}">Kampanye</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span>{{ \Illuminate\Support\Str::limit($kampanye->judul, 40) }}</span>
        </div>

        <div class="panti-detail-grid">
            <div class="panti-detail-photo">
                <img src="{{ asset('assets/figma/campaign-1.jpeg') }}" alt="{{ $kampanye->judul }}">
                @if($kampanye->kategori)
                <div class="panti-detail-photo-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    {{ $kampanye->kategori }}
                </div>
                @endif
            </div>

            <div class="panti-detail-info">
                <div class="panti-verified-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Kampanye Terverifikasi
                </div>
                <h1>{{ $kampanye->judul }}</h1>
                <div class="panti-location-detail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    {{ $kampanye->panti->nama }} · {{ $kampanye->panti->kota }}
                </div>
                <p>{{ $kampanye->deskripsi }}</p>

                {{-- Funding box --}}
                <div style="padding:18px;border:1.5px solid var(--line);border-radius:14px;margin-bottom:20px">
                    <div class="campaign-raised" style="margin-bottom:8px">
                        <strong style="font-size:22px;font-family:'Poppins',sans-serif">Rp {{ number_format($raised, 0, ',', '.') }}</strong>
                        <span style="font-size:14px;color:var(--green-deep);font-weight:700">{{ $progress }}% terkumpul</span>
                    </div>
                    <div class="progress-bar" style="margin-bottom:10px"><div class="progress-bar-fill" style="width:{{ $progress }}%"></div></div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--muted)">
                        <span>Target Rp {{ number_format($kampanye->target_dana, 0, ',', '.') }}</span>
                        <span>Berakhir {{ $kampanye->tanggal_selesai->translatedFormat('d M Y') }}</span>
                    </div>
                </div>

                @if(!auth()->check() || auth()->user()->role === 'donatur')
                <a class="btn btn-primary btn-wide" href="{{ auth()->check() ? route('donatur.donate', $kampanye) : route('login') }}" style="border-radius:12px">
                    Donasi untuk Kampanye Ini
                </a>
                @else
                <div class="notice-card">
                    <strong>Donasi tersedia untuk akun donatur.</strong>
                    <p>Keluar dari akun ini lalu masuk sebagai donatur untuk melanjutkan.</p>
                </div>
                @endif

                <div class="donation-note" style="margin-top:12px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Setiap transaksi diverifikasi sebelum ditampilkan sebagai donasi berhasil.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- About + Impact --}}
<section class="section">
    <div class="container" style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:56px;align-items:start">
        <div>
            <div class="eyebrow"><span class="eyebrow-line"></span> Cerita di balik kampanye</div>
            <h2>Mengapa bantuan Anda penting?</h2>
            <p style="font-size:15px;line-height:1.85">{{ $kampanye->deskripsi }}</p>
            <a class="panti-card" href="{{ route('panti.show', $kampanye->panti) }}" style="display:flex;align-items:center;gap:14px;padding:16px;border-radius:14px;margin-top:20px;border:1px solid var(--line)">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--mint-soft);display:grid;place-items:center;flex-shrink:0;color:var(--green-deep)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div style="flex:1">
                    <strong style="display:block;font-size:14px;color:var(--teal-dark)">{{ $kampanye->panti->nama }}</strong>
                    <small style="color:var(--muted);font-size:12px">Lokasi {{ $kampanye->panti->kota }} · {{ $kampanye->panti->jumlah_anak }} anak binaan</small>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted)"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        <div style="padding:24px;background:var(--mint-soft);border-radius:20px">
            <div class="eyebrow"><span class="eyebrow-line"></span> Laporan transparan</div>
            <h3 style="font-size:18px">Donasi yang terverifikasi</h3>
            <strong style="display:block;font-size:38px;font-family:'Poppins',sans-serif;color:var(--teal-dark);margin:8px 0">{{ $kampanye->jumlah_donatur }}</strong>
            <p style="font-size:14px;margin-bottom:18px">donatur telah ikut membantu kampanye ini.</p>
            <a class="btn btn-outline btn-sm" href="{{ route('reports.index') }}">
                Lihat laporan penyaluran
            </a>
        </div>
    </div>
</section>

@media (max-width: 720px) {
.container[style*="grid-template-columns:1.2fr"] { display: block !important; }
}
@endsection
