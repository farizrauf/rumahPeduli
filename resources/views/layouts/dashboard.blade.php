@extends('layouts.app')

@section('content')
<div class="dashboard-shell">
    {{-- ─── SIDEBAR ─── --}}
    <aside id="dashboard-sidebar" class="dashboard-sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/figma/logo.png') }}" alt="Rumah Peduli">
            <span>Rumah Peduli</span>
        </div>

        @if (auth()->user()->role === 'admin')
            <div class="sidebar-section-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.panti') }}" class="sidebar-nav-link {{ request()->routeIs('admin.panti') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Kelola Panti
            </a>
            <a href="{{ route('admin.campaigns') }}" class="sidebar-nav-link {{ request()->routeIs('admin.campaigns') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Kampanye
            </a>
            <a href="{{ route('admin.donations') }}" class="sidebar-nav-link {{ request()->routeIs('admin.donations') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Donasi
            </a>
            <a href="{{ route('admin.expenses') }}" class="sidebar-nav-link {{ request()->routeIs('admin.expenses') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Pengeluaran
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Pengguna
            </a>
            <a href="{{ route('admin.reports') }}" class="sidebar-nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Laporan
            </a>
            <a href="{{ route('admin.audit') }}" class="sidebar-nav-link {{ request()->routeIs('admin.audit') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Audit Aktivitas
            </a>

        @elseif (auth()->user()->role === 'panti')
            <div class="sidebar-section-label">Ruang Kerja</div>
            <a href="{{ route('panti.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('panti.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Ringkasan
            </a>
            <a href="{{ route('panti.items', 'kampanye') }}" class="sidebar-nav-link {{ request()->routeIs('panti.items') && request()->segment(3) === 'kampanye' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Kampanye
            </a>
            <a href="{{ route('panti.items', 'kebutuhan') }}" class="sidebar-nav-link {{ request()->routeIs('panti.items') && request()->segment(3) === 'kebutuhan' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Kebutuhan
            </a>
            <a href="{{ route('panti.items', 'kegiatan') }}" class="sidebar-nav-link {{ request()->routeIs('panti.items') && request()->segment(3) === 'kegiatan' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Kegiatan
            </a>
            <a href="{{ route('panti.items', 'pengeluaran') }}" class="sidebar-nav-link {{ request()->routeIs('panti.items') && request()->segment(3) === 'pengeluaran' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Pengeluaran
            </a>
            <a href="{{ route('reports.index') }}" class="sidebar-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Transparansi
            </a>

        @else {{-- donatur --}}
            <div class="sidebar-section-label">Ruang Saya</div>
            <a href="{{ route('donatur.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('donatur.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Ringkasan
            </a>
            <a href="{{ route('donatur.donations') }}" class="sidebar-nav-link {{ request()->routeIs('donatur.donations') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Riwayat Donasi
            </a>
            <a href="{{ route('campaigns.index') }}" class="sidebar-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Jelajahi Kampanye
            </a>
        @endif

        <div class="sidebar-profile">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="sidebar-profile-info">
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ ucfirst(auth()->user()->role) }}</small>
            </div>
            <span class="sidebar-status" aria-label="Status aktif"></span>
        </div>
    </aside>

    {{-- ─── MAIN ─── --}}
    <section class="dashboard-main">
        <div class="dashboard-topbar">
            <button class="dashboard-menu-toggle" type="button" aria-label="Buka menu dashboard" aria-controls="dashboard-sidebar" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
            <div class="dashboard-topbar-title">
                Rumah Peduli <span>/ @yield('section-name', 'Dashboard')</span>
            </div>
            <div class="dashboard-topbar-actions">
                <a class="topbar-link" href="{{ route('home') }}">Lihat situs</a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button class="btn btn-outline btn-sm" type="submit">Keluar</button>
                </form>
            </div>
        </div>
        <div class="dashboard-content">
            @yield('dashboard-content')
        </div>
    </section>
</div>

@push('scripts')
<script>
    const dashboardToggle = document.querySelector('.dashboard-menu-toggle');
    const dashboardShell = document.querySelector('.dashboard-shell');
    if (dashboardToggle && dashboardShell) {
        dashboardToggle.addEventListener('click', () => {
            const isOpen = dashboardShell.classList.toggle('sidebar-open');
            dashboardToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }
</script>
@endpush
@endsection
