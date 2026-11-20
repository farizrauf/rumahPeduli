@extends('layouts.app')
@section('title', 'Panti Terverifikasi')
@section('content')

<section class="page-hero">
    <div class="container">
        <div class="eyebrow"><span class="eyebrow-line"></span> Direktori panti terpercaya</div>
        <h1>Panti yang Terdaftar</h1>
        <p>Temukan panti terverifikasi di berbagai kota dan salurkan kepedulian Anda pada kebutuhan yang paling berarti.</p>

        <form class="search-bar" method="GET">
            <div class="search-bar-input">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input name="q" value="{{ request('q') }}" placeholder="Cari nama panti atau lokasi...">
            </div>
            <div class="search-bar-divider"></div>
            <select name="kota">
                <option value="">Kota</option>
                @foreach ($cities as $city)
                    <option value="{{ $city }}" @selected(request('kota') === $city)>{{ $city }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary btn-sm" type="submit">Cari</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="result-info">
            <span>{{ $panti->total() }} panti ditemukan</span>
            <span>Menampilkan {{ $panti->firstItem() }}–{{ $panti->lastItem() }} dari {{ $panti->total() }} panti</span>
        </div>

        <div class="cards-grid" style="margin-top:24px">
            @forelse ($panti as $index => $item)
            <article class="panti-card">
                <a class="panti-card-image" href="{{ route('panti.show', $item) }}">
                    <img src="{{ $item->foto ? \Illuminate\Support\Facades\Storage::disk('public')->url($item->foto) : asset('assets/figma/panti-'.(($index % 3) + 1).'.jpeg') }}" alt="{{ $item->nama }}" loading="lazy">
                    <span class="panti-verified-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Terverifikasi
                    </span>
                </a>
                <div class="panti-card-body">
                    <div class="panti-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $item->kota }}
                    </div>
                    <h3><a href="{{ route('panti.show', $item) }}" style="color:inherit">{{ $item->nama }}</a></h3>
                    <p>{{ \Illuminate\Support\Str::limit($item->deskripsi, 100) }}</p>
                    <div class="panti-need-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        <div>
                            <span style="font-size:9px;font-weight:700;letter-spacing:.5px;color:var(--teal);display:block">KEBUTUHAN PRIORITAS</span>
                            <span style="font-size:12px">{{ $item->needs->where('status','dibutuhkan')->first()?->nama_barang ?? 'Kebutuhan harian' }}</span>
                        </div>
                    </div>
                </div>
                <div class="panti-card-footer">
                    <a href="{{ route('panti.show', $item) }}">
                        Lihat Detail
                    </a>
                </div>
            </article>
            @empty
            <div class="empty-state">Tidak ada panti yang cocok dengan pencarian Anda.</div>
            @endforelse
        </div>

        <div class="pagination-wrap">{{ $panti->links() }}</div>
    </div>
</section>

@endsection
