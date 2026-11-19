<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rumah Peduli') · Rumah Peduli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..800;1,14..32,300..800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- HEADER --}}
    <header class="site-header">
        <div class="container nav-wrap">
            <a href="{{ route('home') }}" class="brand">
                <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
            </a>

            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="{{ request()->routeIs('panti.*') ? 'active' : '' }}" href="{{ route('panti.index') }}">Panti</a>
                <a class="{{ request()->routeIs('campaigns.*') ? 'active' : '' }}" href="{{ route('campaigns.index') }}">Kampanye</a>
                <a class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Kegiatan</a>
            </nav>

            <div class="nav-actions">
                @auth
                    <a class="nav-dashboard" href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline btn-sm" type="submit">Keluar</button>
                    </form>
                @else
                    <a class="nav-login" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('campaigns.index') }}">
                        Donasi Sekarang
                    </a>
                @endauth
            </div>

            <button class="mobile-menu" type="button" aria-label="Buka navigasi" onclick="document.body.classList.toggle('menu-open')">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <x-modal-notification />

    <main>
        @yield('content')
    </main>

    {{-- FOOTER — hanya halaman publik --}}
    @php
        $showFooter = request()->routeIs(
            'home',
            'panti.index',
            'panti.show',
            'campaigns.index',
            'campaigns.show',
            'reports.index',
            'donatur.donate',
        );
    @endphp

    @if ($showFooter)
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                {{-- Brand --}}
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
                    </div>
                    <h4>Satu Kepedulian, Sejuta Harapan.</h4>
                    <p>Platform kemanusiaan yang mempertemukan donatur dengan panti dan kampanye tepercaya di Indonesia.</p>
                </div>

                {{-- Navigasi --}}
                <div class="footer-col">
                    <h5>Navigasi</h5>
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('panti.index') }}">Panti</a>
                    <a href="{{ route('campaigns.index') }}">Kampanye</a>
                    <a href="{{ route('reports.index') }}">Kegiatan</a>
                    <a href="{{ route('login') }}">Masuk</a>
                </div>

                {{-- Hubungi Kami --}}
                <div class="footer-col">
                    <h5>Hubungi Kami</h5>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <span>halo@rumahpeduli.id</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5 19.79 19.79 0 0 1 1.61 5C1.6 3.98 2.3 3.12 3.28 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>+62 21 5084 2026</span>
                    </div>
                    <div class="footer-contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Jl. Kepedulian No. 18, Jakarta Selatan</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© {{ date('Y') }} Rumah Peduli. Seluruh hak dilindungi.</span>
                <div class="footer-bottom-links">
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
    @endif

    @stack('scripts')
</body>
</html>
