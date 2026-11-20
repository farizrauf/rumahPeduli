@extends('layouts.app')
@section('title', $panti->nama)
@section('content')

{{-- ─── HERO ─── --}}
<section class="panti-detail-hero">
    <div class="container">
        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <a href="{{ route('panti.index') }}">Panti</a>
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span>{{ $panti->nama }}</span>
        </div>

        <div class="panti-detail-grid">
            {{-- Photo --}}
            <div class="panti-detail-photo">
                <img src="{{ $panti->foto ? \Illuminate\Support\Facades\Storage::disk('public')->url($panti->foto) : asset('assets/figma/panti-1.jpeg') }}" alt="{{ $panti->nama }}">
                <div class="panti-detail-photo-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ $panti->jumlah_anak }} Anak &nbsp;·&nbsp; bertumbuh bersama
                </div>
            </div>

            {{-- Info --}}
            <div class="panti-detail-info">
                <div class="panti-verified-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Panti Terverifikasi
                </div>
                <h1>{{ $panti->nama }}</h1>
                <div class="panti-location-detail">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $panti->kota }}
                </div>
                @if($panti->needs->where('status','dibutuhkan')->isNotEmpty())
                <div class="panti-priority-need">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    Kebutuhan {{ $panti->needs->where('status','dibutuhkan')->first()?->kategori ?? 'Harian' }}
                </div>
                @endif
                <p>{{ $panti->deskripsi }}</p>
                <div class="panti-detail-actions">
                    @if($panti->campaigns->isNotEmpty())
                    <a class="btn btn-primary" href="{{ auth()->check() && auth()->user()->role === 'donatur' ? route('donatur.donate', $panti->campaigns->first()) : (auth()->check() ? route('campaigns.show', $panti->campaigns->first()) : route('login')) }}">
                        Donasi Sekarang
                    </a>
                    @endif
                    <button class="btn btn-outline" type="button" data-share-title="{{ $panti->nama }}">
                        Bagikan
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── DONATION SECTION ─── --}}
@if($panti->campaigns->isNotEmpty())
@php($campaign = $panti->campaigns->first())
@php($raised = $campaign->donasi()->where('status','verified')->sum('nominal'))
@php($progress = $campaign->target_dana > 0 ? min(100, round($raised / $campaign->target_dana * 100)) : 0)
@php($donorCount = $campaign->donasi()->where('status','verified')->count())
@php($remaining = max(0, $campaign->target_dana - $raised))
@php($selectedAmount = (int) old('nominal', 100000))
<section class="donation-section">
    <div class="container">
        <div class="donation-section-header">
            <div class="eyebrow" style="justify-content:center"><span class="eyebrow-line"></span> Hadirkan kebaikan hari ini</div>
            <h2>Bantu Penuhi Kebutuhan Mereka</h2>
            <p>Satu donasi, langkah nyata untuk nutrisi yang lebih baik dan pendidikan yang berkelanjutan.</p>
        </div>
        <div class="donation-box">
            <div>
                <p style="font-size:13px;color:var(--muted);margin:0 0 4px">Terkumpul</p>
                <div class="donation-raised">
                    <strong>Rp {{ number_format($raised, 0, ',', '.') }}</strong>
                </div>
                <p style="font-size:13px;color:var(--muted);margin:4px 0 8px">Target Rp {{ number_format($campaign->target_dana, 0, ',', '.') }}</p>
                <div class="progress-bar" style="margin-bottom:8px"><div class="progress-bar-fill" style="width:{{ $progress }}%"></div></div>
                <p style="font-size:12px;color:var(--green-deep);font-weight:700;margin:0 0 12px;text-align:right">{{ $progress }}%</p>
                <div class="donation-donors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span><strong>{{ $donorCount }} Donatur</strong> telah ikut membantu</span>
                </div>
                @if($remaining > 0)
                <div class="donation-still-needed">
                    <strong>Masih dibutuhkan Rp {{ number_format($remaining, 0, ',', '.') }}</strong>
                    <p>Dukungan kamu berarti bagi keseharian dan masa depan {{ $panti->jumlah_anak }} anak di {{ $panti->nama }}.</p>
                </div>
                @endif
            </div>
            <div>
                <div class="donation-nominal-label">Pilih nominal donasi</div>
                @auth
                    @if(auth()->user()->role === 'donatur')
                    <form action="{{ route('donatur.donate.store', $campaign) }}" method="POST" id="donate-form">
                        @csrf
                        <div class="amount-grid">
                            <button type="button" class="amount-btn {{ $selectedAmount === 25000 ? 'active' : '' }}" data-amount="25000" aria-pressed="{{ $selectedAmount === 25000 ? 'true' : 'false' }}">Rp25.000</button>
                            <button type="button" class="amount-btn {{ $selectedAmount === 50000 ? 'active' : '' }}" data-amount="50000" aria-pressed="{{ $selectedAmount === 50000 ? 'true' : 'false' }}">Rp50.000</button>
                            <button type="button" class="amount-btn {{ $selectedAmount === 100000 ? 'active' : '' }}" data-amount="100000" aria-pressed="{{ $selectedAmount === 100000 ? 'true' : 'false' }}">Rp100.000</button>
                            <button type="button" class="amount-btn {{ $selectedAmount === 250000 ? 'active' : '' }}" data-amount="250000" aria-pressed="{{ $selectedAmount === 250000 ? 'true' : 'false' }}">Rp250.000</button>
                        </div>
                        <div class="amount-custom-wrap">
                            <span class="amount-custom-prefix">Rp</span>
                            <input type="number" name="nominal" id="nominal-input" value="{{ old('nominal', 100000) }}" placeholder="Nominal lainnya" min="1000" step="1" inputmode="numeric" aria-describedby="@error('nominal') panti-donate-nominal-error @enderror" required>
                        </div>
                        @error('nominal')
                            <small class="field-error" id="panti-donate-nominal-error">{{ $message }}</small>
                        @enderror
                        <div class="auth-form-group" style="margin-top:8px">
                            <label for="panti-donate-method" style="font-size:11px;font-weight:700;color:var(--teal)">Metode Pembayaran</label>
                            <select id="panti-donate-method" name="metode" aria-describedby="@error('metode') panti-donate-method-error @enderror" required>
                                <option value="">Pilih metode...</option>
                                <option value="Transfer BCA" @selected(old('metode') === 'Transfer BCA')>Transfer BCA</option>
                                <option value="Transfer Mandiri" @selected(old('metode') === 'Transfer Mandiri')>Transfer Mandiri</option>
                                <option value="QRIS" @selected(old('metode') === 'QRIS')>QRIS</option>
                                <option value="GoPay" @selected(old('metode') === 'GoPay')>GoPay</option>
                            </select>
                        </div>
                        @error('metode')
                            <small class="field-error" id="panti-donate-method-error">{{ $message }}</small>
                        @enderror
                        <button class="btn btn-primary btn-wide" type="submit" style="border-radius:12px">
                            Lanjutkan Donasi
                        </button>
                        <div class="donation-note">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Donasi kamu akan diverifikasi dan disalurkan kepada panti.
                        </div>
                    </form>
                    @else
                    <div class="notice-card">
                        <strong>Donasi tersedia untuk akun donatur.</strong>
                        <p>Keluar lalu masuk sebagai donatur untuk melanjutkan.</p>
                    </div>
                    @endif
                @else
                <div class="amount-grid">
                    <span class="amount-btn">Rp25.000</span>
                    <span class="amount-btn">Rp50.000</span>
                    <span class="amount-btn active">Rp100.000</span>
                    <span class="amount-btn">Rp250.000</span>
                </div>
                <a class="btn btn-primary btn-wide" href="{{ route('login') }}" style="border-radius:12px;margin-top:8px">
                    Masuk untuk Donasi
                </a>
                <div class="donation-note" style="margin-top:10px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Donasi kamu akan diverifikasi dan disalurkan kepada panti.
                </div>
                @endauth
            </div>
        </div>
    </div>
</section>
@endif

{{-- ─── ABOUT ─── --}}
<section class="about-section">
    <div class="container about-grid">
        <div>
            <div class="eyebrow"><span class="eyebrow-line"></span> Kenali lebih dekat</div>
            <h2>Tentang {{ $panti->nama }}</h2>
            <p style="font-size:15px;line-height:1.8">{{ $panti->deskripsi }}</p>
            <div class="about-tagline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Tempat bertumbuh, belajar, dan bermimpi.
            </div>
        </div>
        <div>
            <p style="font-size:15px;line-height:1.85">{{ $panti->nama }} di {{ $panti->kota }} menjadi rumah bagi {{ $panti->jumlah_anak }} anak. Di sini, perhatian sehari-hari dan kebersamaan membantu anak-anak tumbuh dengan rasa aman dan harapan.</p>
            <p style="font-size:15px;line-height:1.85">Melalui pemenuhan nutrisi, pendampingan belajar, serta kegiatan kreatif bersama pengasuh dan relawan, panti berupaya memberi setiap anak kesempatan untuk mengembangkan potensi. Dukungan kamu membantu menjaga kebutuhan dasar mereka tetap terpenuhi.</p>
        </div>
    </div>
</section>

{{-- ─── NEEDS ─── --}}
@if($panti->needs->isNotEmpty())
<section class="needs-section">
    <div class="container">
        <div class="eyebrow"><span class="eyebrow-line"></span> Kepedulian yang tepat sasaran</div>
        <h2>Kebutuhan Saat Ini</h2>
        <p>{{ $panti->needs->count() > 1 ? $panti->needs->count().' kebutuhan utama' : 'Kebutuhan utama' }} yang bisa kita penuhi bersama untuk keseharian anak-anak.</p>
        <div class="needs-grid">
            @foreach($panti->needs as $need)
            <div class="need-card">
                <div class="need-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h4>{{ $need->nama_barang }}</h4>
                <p>{{ $need->kategori ?? 'Kebutuhan harian' }}</p>
                @if($need->jumlah)
                <div class="need-card-detail">{{ $need->jumlah }} unit dibutuhkan</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── ACTIVITIES + LOCATION ─── --}}
<section class="activities-location-section">
    <div class="container activities-location-grid">
        <div>
            <div class="eyebrow"><span class="eyebrow-line"></span> Ikuti perjalanan kebaikan</div>
            <h2>Perkembangan Terbaru</h2>
            @if($panti->activities->isNotEmpty())
            <div class="activity-timeline">
                @foreach($panti->activities as $activity)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div>
                        <div class="timeline-date">{{ optional($activity->tanggal)->format('d M Y') }}</div>
                        <div class="timeline-title">{{ $activity->judul }}</div>
                        <p class="timeline-desc">{{ \Illuminate\Support\Str::limit($activity->deskripsi, 120) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:var(--muted)">Belum ada kegiatan terbaru.</p>
            @endif
        </div>

        <div>
            <div class="eyebrow"><span class="eyebrow-line"></span> Dekat dengan kepedulian</div>
            <h2>Lokasi Panti</h2>
            <div class="location-card">
                <div class="location-map">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="location-info">
                    <h4>{{ $panti->kota }}</h4>
                    <p>Lokasi ditampilkan pada tingkat kota. Alamat lengkap tidak dicantumkan pada halaman ini.</p>
                    <a class="btn btn-outline btn-sm" href="https://maps.google.com/search/{{ urlencode($panti->kota . ' ' . $panti->nama) }}" target="_blank" rel="noopener noreferrer">
                        Lihat di Google Maps
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function setAmount(amount) {
    const input = document.getElementById('nominal-input');
    if (input) {
        input.value = amount;
        document.querySelectorAll('.amount-btn[data-amount]').forEach(btn => {
            const isSelected = Number(btn.dataset.amount) === Number(amount);
            btn.classList.toggle('active', isSelected);
            btn.setAttribute('aria-pressed', String(isSelected));
        });
    }
}

document.querySelector('[data-share-title]')?.addEventListener('click', async (event) => {
    const button = event.currentTarget;
    const shareData = { title: button.dataset.shareTitle, url: window.location.href };

    if (navigator.share) {
        try {
            await navigator.share(shareData);
            return;
        } catch (error) {
            if (error.name === 'AbortError') return;
        }
    }

    try {
        await navigator.clipboard.writeText(shareData.url);
        alert('Tautan panti berhasil disalin.');
    } catch (error) {
        window.prompt('Salin tautan panti ini:', shareData.url);
    }
});

document.querySelectorAll('.amount-btn[data-amount]').forEach((button) => {
    button.addEventListener('click', () => setAmount(button.dataset.amount));
});

document.getElementById('nominal-input')?.addEventListener('input', (event) => {
    const amount = Number(event.currentTarget.value);
    document.querySelectorAll('.amount-btn[data-amount]').forEach((button) => {
        const isSelected = Number(button.dataset.amount) === amount;
        button.classList.toggle('active', isSelected);
        button.setAttribute('aria-pressed', String(isSelected));
    });
});
</script>
@endpush
@endsection
