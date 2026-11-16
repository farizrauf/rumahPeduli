@extends('layouts.app')
@section('title', 'Laporan Transparansi')
@section('content')

{{-- HERO SECTION --}}
<section class="transparency-hero">
    <div class="container">
        <div class="transparency-hero-content">
            <div class="transparency-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                Transparansi Penuh
            </div>
            <h1>Setiap Rupiah, Terhitung Jelas</h1>
            <p class="hero-description">Platform terbuka untuk memastikan donasi Anda tersalurkan dengan tepat. Hanya transaksi terverifikasi yang masuk ke laporan.</p>
        </div>
    </div>
</section>

{{-- FINANCIAL SUMMARY --}}
<section class="financial-summary">
    <div class="container">
        <div class="summary-cards">
            {{-- Total Donasi --}}
            <div class="summary-card card-donations">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="summary-content">
                    <span class="summary-label">Donasi Terverifikasi</span>
                    <h2 class="summary-value">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</h2>
                    <p class="summary-desc">Akumulasi bantuan terkonfirmasi</p>
                </div>
            </div>

            {{-- Total Penyaluran --}}
            <div class="summary-card card-expenses">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="summary-content">
                    <span class="summary-label">Dana Tersalurkan</span>
                    <h2 class="summary-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                    <p class="summary-desc">Pengeluaran terverifikasi admin</p>
                </div>
            </div>

            {{-- Saldo --}}
            <div class="summary-card card-balance">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                </div>
                <div class="summary-content">
                    <span class="summary-label">Saldo Platform</span>
                    <h2 class="summary-value">Rp {{ number_format(max(0, $totalDonasi - $totalPengeluaran), 0, ',', '.') }}</h2>
                    <p class="summary-desc">Selisih donasi & penyaluran</p>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        @php
            $disbursementRate = $totalDonasi > 0 ? ($totalPengeluaran / $totalDonasi) * 100 : 0;
        @endphp
        <div class="disbursement-progress">
            <div class="progress-header">
                <span class="progress-label">Tingkat Penyaluran</span>
                <span class="progress-percent">{{ number_format($disbursementRate, 1) }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ min(100, $disbursementRate) }}%"></div>
            </div>
            <p class="progress-note">Dana yang telah disalurkan dari total donasi terverifikasi</p>
        </div>
    </div>
</section>

{{-- BREAKDOWN PER PANTI --}}
<section class="panti-breakdown">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">RINCIAN PER PANTI</span>
            <h2>Transparansi Penyaluran Dana</h2>
            <p>Lihat bagaimana donasi Anda disalurkan ke setiap panti asuhan</p>
        </div>

        @if($panti->count() > 0)
        <div class="panti-report-grid">
            @foreach($panti as $item)
            @php
                $pantiDonasi = $item->total_donasi ?? 0;
                $pantiPengeluaran = $item->total_pengeluaran ?? 0;
                $pantiSaldo = max(0, $pantiDonasi - $pantiPengeluaran);
                $pantiRate = $pantiDonasi > 0 ? ($pantiPengeluaran / $pantiDonasi) * 100 : 0;
            @endphp
            <div class="panti-report-card">
                <div class="panti-report-header">
                    <div class="panti-avatar">{{ strtoupper(substr($item->nama, 0, 2)) }}</div>
                    <div class="panti-info">
                        <h3 class="panti-name">{{ $item->nama }}</h3>
                        <p class="panti-location">{{ $item->kota }}</p>
                    </div>
                </div>

                <div class="panti-metrics">
                    <div class="panti-metric">
                        <span class="metric-label">Donasi Masuk</span>
                        <span class="metric-value metric-in">Rp {{ number_format($pantiDonasi, 0, ',', '.') }}</span>
                    </div>
                    <div class="panti-metric">
                        <span class="metric-label">Dana Tersalurkan</span>
                        <span class="metric-value metric-out">Rp {{ number_format($pantiPengeluaran, 0, ',', '.') }}</span>
                    </div>
                    <div class="panti-metric">
                        <span class="metric-label">Saldo Tercatat</span>
                        <span class="metric-value metric-balance">Rp {{ number_format($pantiSaldo, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="panti-progress">
                    <div class="panti-progress-bar">
                        <div class="panti-progress-fill" style="width: {{ min(100, $pantiRate) }}%"></div>
                    </div>
                    <span class="panti-progress-label">{{ number_format($pantiRate, 0) }}% tersalurkan</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $panti->links() }}
        </div>
        @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <h3>Belum Ada Laporan</h3>
            <p>Laporan akan tersedia setelah transaksi diverifikasi oleh admin.</p>
        </div>
        @endif
    </div>
</section>

@endsection
