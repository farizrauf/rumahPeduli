@extends('layouts.dashboard')
@section('title', 'Dashboard Donatur')
@section('section-name', 'Ringkasan')

@section('dashboard-content')

{{-- HEADER --}}
<div class="dashboard-heading">
    <div>
        <span class="eyebrow">RUANG DONATUR</span>
        <h1>Halo, {{ auth()->user()->name }}</h1>
        <p>Terima kasih telah menjadi bagian dari setiap cerita baik.</p>
    </div>
    <a class="button button-primary" href="{{ route('campaigns.index') }}">
        Cari kampanye
    </a>
</div>

{{-- METRICS --}}
<div class="metric-grid">
    <article class="metric-card">
        <span>Total donasi terverifikasi</span>
        <strong>Rp {{ number_format($total,0,',','.') }}</strong>
        <small>Kontribusi yang telah dikonfirmasi</small>
    </article>
    
    <article class="metric-card metric-green">
        <span>Donasi berhasil</span>
        <strong>{{ $count }}</strong>
        <small>Transaksi yang telah diverifikasi</small>
    </article>
    
    <article class="metric-card">
        <span>Perlu verifikasi</span>
        <strong>{{ $donations->where('status','pending')->count() }}</strong>
        <small>Donasi Anda sedang ditinjau</small>
    </article>
</div>

{{-- DONATION HISTORY --}}
<div class="section-heading table-heading">
    <div>
        <span class="eyebrow">AKTIVITAS TERBARU</span>
        <h2>Riwayat donasi</h2>
    </div>
    <a class="text-link" href="{{ route('donatur.donations') }}">
        Lihat semua
    </a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Kode transaksi</th>
                <th>Kampanye</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $donation)
            <tr>
                <td><strong>{{ $donation->kode_transaksi }}</strong></td>
                <td>{{ $donation->kampanye->judul }}</td>
                <td>{{ $donation->created_at->format('d M Y') }}</td>
                <td>Rp {{ number_format($donation->nominal,0,',','.') }}</td>
                <td>
                    <span class="status-badge status-{{ $donation->status }}">
                        {{ ucfirst($donation->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-inline">
                    Belum ada donasi. <a href="{{ route('campaigns.index') }}">Temukan kampanye pertama Anda.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
