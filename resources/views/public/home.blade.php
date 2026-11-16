@extends('layouts.app')
@section('title', 'Satu Kepedulian, Sejuta Harapan')
@section('content')

{{-- ═══════════════════════════════════════ HERO ══════════════════════════════════════ --}}
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="hero-kicker">
                <span></span>
                Gerakan kebaikan bersama
            </div>
            <h1>Bersama Kita Bisa Membantu <span class="accent">Mereka</span></h1>
            <p>Hubungkan kepedulian Anda dengan panti dan kampanye tepercaya di seluruh Indonesia. Setiap bantuan diverifikasi, disalurkan, dan dilaporkan secara transparan.</p>
            <div class="hero-actions">
                <a class="btn btn-primary btn-lg" href="{{ route('campaigns.index') }}">
                    Donasi Sekarang
                </a>
                <a class="btn btn-outline btn-lg" href="{{ route('panti.index') }}">Jelajahi Panti</a>
            </div>
            <div class="hero-trust">
                <div class="check-icon">✓</div>
                <span>Terverifikasi &nbsp;·&nbsp; Transparan &nbsp;·&nbsp; Berdampak nyata</span>
            </div>
        </div>
        <div class="hero-media">
            <img class="hero-photo" src="{{ asset('assets/figma/hero.png') }}" alt="Anak-anak belajar bersama relawan">
            <div class="hero-badge">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
                <div>
                    <strong>1.240+ keluarga</strong>
                    <small>telah menerima bantuan</small>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ STATS ══════════════════════════════════════ --}}
<section class="stats-strip">
    <div class="container stats-grid">
        <div class="stat-item">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <strong>{{ number_format($pantiCount ?: 126) }}</strong>
                <span>Total Panti</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <strong>{{ number_format($donorCount ?: 18420) }}+</strong>
                <span>Total Donatur</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div>
                <strong>Rp {{ number_format($donationTotal / 1000000000, 1, ',', '.') }}M</strong>
                <span>Total Bantuan</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ PANTI PILIHAN ══════════════════════════════════════ --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-header-text">
                <div class="eyebrow"><span class="eyebrow-line"></span> Panti pilihan</div>
                <h2>Panti yang Membutuhkan Bantuan</h2>
                <p>Temukan panti terverifikasi dengan kebutuhan paling mendesak dan bantu mereka bertumbuh dengan layak.</p>
            </div>
            <a class="btn btn-outline btn-sm" href="{{ route('panti.index') }}" style="white-space:nowrap">Lihat semua panti</a>
        </div>

        <div class="cards-grid">
            @forelse ($pantiList as $index => $panti)
            <article class="panti-card">
                <a class="panti-card-image" href="{{ route('panti.show', $panti) }}">
                    <img src="{{ asset('assets/figma/panti-'.(($index % 3) + 1).'.jpeg') }}" alt="{{ $panti->nama }}" loading="lazy">
                    <span class="panti-verified-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Terverifikasi
                    </span>
                </a>
                <div class="panti-card-body">
                    <div class="panti-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $panti->kota }}
                    </div>
                    <h3><a href="{{ route('panti.show', $panti) }}" style="color:inherit">{{ $panti->nama }}</a></h3>
                    <p>{{ \Illuminate\Support\Str::limit($panti->deskripsi, 90) }}</p>
                    <div class="panti-need-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <div>
                            <span>KEBUTUHAN PRIORITAS</span>
                            <small>{{ $panti->needs->where('status','dibutuhkan')->first()?->nama_barang ?? 'Kebutuhan harian' }}</small>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        {{ $panti->jumlah_anak }} anak
                    </div>
                </div>
                <div class="panti-card-footer">
                    <a href="{{ route('panti.show', $panti) }}">
                        Lihat detail
                    </a>
                </div>
            </article>
            @empty
            <div class="empty-state">Panti terverifikasi akan segera hadir.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ KAMPANYE ══════════════════════════════════════ --}}
<section class="section section-mint">
    <div class="container">
        <div class="section-header">
            <div class="section-header-text">
                <div class="eyebrow"><span class="eyebrow-line"></span> Kampanye aktif</div>
                <h2>Kampanye Terbaru</h2>
                <p>Setiap kampanye memiliki target jelas, pembaruan rutin, dan pelaporan penyaluran yang dapat Anda ikuti.</p>
            </div>
            <a class="btn btn-outline btn-sm" href="{{ route('campaigns.index') }}" style="white-space:nowrap">Semua kampanye</a>
        </div>

        <div class="campaign-cards-grid">
            @forelse ($campaigns as $index => $campaign)
            @php($raised = $campaign->dana_terkumpul ?? 0)
            @php($progress = $campaign->target_dana > 0 ? min(100, round($raised / $campaign->target_dana * 100)) : 0)
            @php($sisa = max(0, ($campaign->tanggal_selesai ? now()->diffInDays($campaign->tanggal_selesai, false) : 30)))
            <article class="campaign-card">
                <a class="campaign-card-image" href="{{ route('campaigns.show', $campaign) }}">
                    <img src="{{ asset('assets/figma/campaign-'.(($index % 2) + 1).'.jpeg') }}" alt="{{ $campaign->judul }}" loading="lazy">
                    <span class="campaign-cat-badge">{{ $campaign->kategori ?? 'Kebaikan' }}</span>
                    <span class="campaign-days-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {{ $sisa }} hari lagi
                    </span>
                </a>
                <div class="campaign-card-body">
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
                    <a href="{{ auth()->check() && auth()->user()->role === 'donatur' ? route('donatur.donate', $campaign) : (auth()->check() ? route('campaigns.show', $campaign) : route('login')) }}">
                        Dukung Kampanye
                    </a>
                </div>
            </article>
            @empty
            <div class="empty-state">Belum ada kampanye aktif.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ PROSES ══════════════════════════════════════ --}}
<section class="process-section">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto 0">
            <div class="eyebrow" style="justify-content:center"><span class="eyebrow-line" style="background:var(--mint)"></span> Proses transparan</div>
            <h2>Setiap Bantuan, Terlihat Dampaknya</h2>
            <p>Kami menjaga penyaluran donasi tetap jelas dari niat baik hingga laporan yang bisa Anda lihat.</p>
        </div>
        <div class="process-grid">
            <div class="process-step">
                <div class="process-step-num">01</div>
                <div class="process-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h3>Donasi</h3>
                <p>Pilih panti atau kampanye dan tentukan bantuan Anda.</p>
            </div>
            <div class="process-step">
                <div class="process-step-num">02</div>
                <div class="process-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3>Verifikasi</h3>
                <p>Tim memastikan kebutuhan dan penerima bantuan tervalidasi.</p>
            </div>
            <div class="process-step">
                <div class="process-step-num">03</div>
                <div class="process-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3>Penyaluran</h3>
                <p>Bantuan disalurkan langsung bersama mitra tepercaya.</p>
            </div>
            <div class="process-step">
                <div class="process-step-num">04</div>
                <div class="process-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h3>Laporan</h3>
                <p>Anda menerima pembaruan dan bukti dampak yang transparan.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════ KEGIATAN ══════════════════════════════════════ --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-header-text">
                <div class="eyebrow"><span class="eyebrow-line"></span> Cerita lapangan</div>
                <h2>Kegiatan Terbaru</h2>
                <p>Lihat bagaimana kepedulian tumbuh menjadi kegiatan nyata bersama panti, relawan, dan masyarakat.</p>
            </div>
            <a class="btn btn-outline btn-sm" href="{{ route('reports.index') }}" style="white-space:nowrap">Lihat semua kegiatan</a>
        </div>

        <div class="activity-grid">
            @forelse ($activities as $index => $activity)
            <article class="activity-card">
                <div class="activity-card-img">
                    <img src="{{ asset('assets/figma/panti-'.(($index % 3) + 1).'.jpeg') }}" alt="{{ $activity->judul }}" loading="lazy">
                </div>
                <div class="activity-card-body">
                    <span class="activity-date">{{ optional($activity->tanggal)->format('d M Y') }}</span>
                    <h3>{{ $activity->judul }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($activity->deskripsi, 100) }}</p>
                    <a class="read-more" href="{{ route('reports.index') }}">
                        Baca cerita
                    </a>
                </div>
            </article>
            @empty
            <div class="empty-state">Belum ada kegiatan terbaru.</div>
            @endforelse
        </div>
    </div>
</section>

@endsection
