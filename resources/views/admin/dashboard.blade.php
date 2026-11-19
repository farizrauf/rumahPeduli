@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')
@section('section-name', 'Dashboard Admin')

@section('dashboard-content')

{{-- HEADER --}}
<div class="dashboard-heading">
    <div>
        <span class="eyebrow">RINGKASAN PLATFORM</span>
        <h1>Selamat datang, {{ auth()->user()->name }}.</h1>
        <p>Pantau kebaikan yang sedang bergerak di Rumah Peduli.</p>
    </div>
    <div class="heading-actions">
        <span class="live-pill"><span></span> Sistem aktif</span>
        <a class="button button-outline" href="{{ route('admin.reports') }}">Lihat laporan</a>
    </div>
</div>

{{-- METRICS GRID --}}
<div class="metric-grid metric-four">
    <article class="metric-card">
        <div class="metric-card-top"><span>Total panti terdaftar</span><span class="metric-icon metric-icon-mint" aria-hidden="true"></span></div>
        <strong>{{ number_format($stats['panti']) }}</strong>
        <small>{{ $stats['panti_pending'] }} pengajuan menunggu verifikasi</small>
    </article>
    
    <article class="metric-card metric-green">
        <div class="metric-card-top"><span>Donatur terdaftar</span><span class="metric-icon metric-icon-green" aria-hidden="true"></span></div>
        <strong>{{ number_format($stats['donatur']) }}</strong>
        <small>Komunitas berbagi kebaikan</small>
    </article>
    
    <article class="metric-card">
        <div class="metric-card-top"><span>Donasi terverifikasi</span><span class="metric-icon metric-icon-blue" aria-hidden="true"></span></div>
        <strong>Rp {{ number_format($stats['donasi']/1000000,1,',','.') }} jt</strong>
        <small>{{ $stats['donasi_pending'] }} transaksi menunggu tinjauan</small>
    </article>
    
    <article class="metric-card metric-green">
        <div class="metric-card-top"><span>Kampanye aktif</span><span class="metric-icon metric-icon-orange" aria-hidden="true"></span></div>
        <strong>{{ $stats['kampanye'] }}</strong>
        <small>Penggalangan dana berjalan</small>
    </article>
</div>

{{-- 2-COLUMN PENDING ITEMS --}}
<div class="admin-columns">
    {{-- PENDING PANTI --}}
    <section>
        <div class="section-heading table-heading">
            <div>
                <span class="eyebrow">TINDAKAN DIPERLUKAN</span>
                <h2>Pengajuan panti terbaru</h2>
            </div>
            <a class="text-link" href="{{ route('admin.panti',['status'=>'pending']) }}">
                Lihat semua
            </a>
        </div>
        
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Panti</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPanti as $panti)
                    <tr>
                        <td>
                            <strong>{{ $panti->nama }}</strong>
                            <small>{{ $panti->user->email }}</small>
                        </td>
                        <td>{{ $panti->kota }}</td>
                        <td>{{ $panti->created_at->format('d M Y') }}</td>
                        <td>
                            <a class="table-action" href="{{ route('admin.panti',['status'=>'pending']) }}">
                                Tinjau
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-inline">
                            Tidak ada pengajuan panti yang menunggu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    
    {{-- PENDING DONATIONS --}}
    <section>
        <div class="section-heading table-heading">
            <div>
                <span class="eyebrow">DONASI BARU</span>
                <h2>Transaksi menunggu</h2>
            </div>
            <a class="text-link" href="{{ route('admin.donations',['status'=>'pending']) }}">
                Lihat semua
            </a>
        </div>
        
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Donatur</th>
                        <th>Kampanye</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingDonations as $donation)
                    <tr>
                        <td>
                            <strong>{{ $donation->user->name }}</strong>
                            <small>{{ $donation->kode_transaksi }}</small>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($donation->kampanye->judul,24) }}</td>
                        <td>Rp {{ number_format($donation->nominal,0,',','.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="empty-inline">
                            Tidak ada donasi pending.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@endsection
