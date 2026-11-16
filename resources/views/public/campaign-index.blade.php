@extends('layouts.app')
@section('title', 'Kampanye Kebaikan')
@section('content')

<section class="page-hero">
    <div class="container">
        <div class="eyebrow"><span class="eyebrow-line"></span> Kampanye terbuka</div>
        <h1>Kampanye yang Membutuhkan Dukungan</h1>
        <p>Setiap kampanye memiliki target jelas, pembaruan rutin, dan pelaporan penyaluran yang dapat Anda ikuti.</p>

        <form class="search-bar" method="GET" style="max-width:600px">
            <select name="kategori" style="flex:1;border:0;outline:none;background:transparent;font-size:14px;color:var(--teal-dark);padding:8px 14px;cursor:pointer;min-width:160px">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <div class="search-bar-divider"></div>
            <button class="btn btn-primary btn-sm" type="submit">Tampilkan</button>
        </form>

        <div class="result-info" style="margin-top:14px">
            <span>{{ $campaigns->total() }} kampanye aktif</span>
            <span>Donasi Anda tercatat dan diverifikasi sebelum dilaporkan</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="campaign-cards-grid">
            @forelse ($campaigns as $index => $campaign)
            @php($raised = $campaign->dana_terkumpul ?? 0)
            @php($progress = $campaign->target_dana > 0 ? min(100, round($raised / $campaign->target_dana * 100)) : 0)
            @php($sisa = max(0, ($campaign->tanggal_selesai ? now()->diffInDays($campaign->tanggal_selesai, false) : 30)))
            <article class="campaign-card">
                <a class="campaign-card-image" href="{{ route('campaigns.show', $campaign) }}">
                    <img src="{{ asset('assets/figma/campaign-'.(($index % 3) + 1).'.jpeg') }}" alt="{{ $campaign->judul }}" loading="lazy">
                    <span class="campaign-cat-badge">{{ $campaign->kategori ?? 'Kebaikan' }}</span>
                    <span class="campaign-days-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $sisa }} hari lagi
                    </span>
                </a>
                <div class="campaign-card-body">
                    <div style="font-size:11px;color:var(--muted)">{{ $campaign->panti->kota }} · {{ $campaign->panti->nama }}</div>
                    <h3><a href="{{ route('campaigns.show', $campaign) }}">{{ $campaign->judul }}</a></h3>
                    <div class="campaign-progress-wrap">
                        <div class="campaign-raised">
                            <strong>Rp {{ number_format($raised, 0, ',', '.') }}</strong>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="progress-bar"><div class="progress-bar-fill" style="width:{{ $progress }}%"></div></div>
                        <p class="campaign-target">Target Rp {{ number_format($campaign->target_dana, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="campaign-card-action">
                    <a href="{{ route('campaigns.show', $campaign) }}">
                        Dukung Kampanye
                    </a>
                </div>
            </article>
            @empty
            <div class="empty-state">Belum ada kampanye aktif saat ini.</div>
            @endforelse
        </div>

        <div class="pagination-wrap">{{ $campaigns->links() }}</div>
    </div>
</section>

@endsection
