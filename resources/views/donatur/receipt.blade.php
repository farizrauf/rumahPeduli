@extends('layouts.app')

@section('title', 'Bukti transaksi '.$donasi->kode_transaksi)

@section('content')
<section class="auth-section">
    <div class="container" style="max-width: 720px">
        <div class="auth-card">
            <a class="back-link" href="{{ route('donatur.donations') }}">← Kembali ke riwayat donasi</a>
            <span class="eyebrow">BUKTI TRANSAKSI</span>
            <h1>Donasi berhasil dicatat</h1>
            <p class="trust-note">Simpan halaman ini sebagai bukti bahwa Rumah Peduli sudah menerima pengajuan donasi Anda.</p>

            <div class="impact-card" style="margin: 24px 0">
                <span class="eyebrow">KODE TRANSAKSI</span>
                <strong>{{ $donasi->kode_transaksi }}</strong>
                <p>{{ $donasi->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div class="table-card">
                <table>
                    <tbody>
                        <tr><th>Donatur</th><td>{{ $donasi->user->name }}</td></tr>
                        <tr><th>Kampanye</th><td>{{ $donasi->kampanye->judul }}</td></tr>
                        <tr><th>Panti</th><td>{{ $donasi->kampanye->panti->nama }}</td></tr>
                        <tr><th>Nominal</th><td><strong>Rp {{ number_format($donasi->nominal, 0, ',', '.') }}</strong></td></tr>
                        <tr><th>Metode</th><td>{{ $donasi->metode }}</td></tr>
                        <tr><th>Status</th><td><span class="status-badge status-{{ $donasi->status }}">{{ ucfirst($donasi->status) }}</span></td></tr>
                    </tbody>
                </table>
            </div>

            @if ($donasi->catatan_admin)
                <p class="trust-note" style="margin-top: 18px">Catatan admin: {{ $donasi->catatan_admin }}</p>
            @endif

            <button class="button button-primary" style="margin-top: 24px" type="button" onclick="window.print()">Cetak / simpan PDF</button>
        </div>
    </div>
</section>
@endsection
