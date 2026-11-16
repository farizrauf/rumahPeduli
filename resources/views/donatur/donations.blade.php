@extends('layouts.dashboard')
@section('title', 'Riwayat Donasi')
@section('section-name', 'Riwayat donasi')
@section('dashboard-content')
<div class="dashboard-heading"><div><span class="eyebrow">TRANSAKSI SAYA</span><h1>Riwayat donasi</h1><p>Periksa status dan detail donasi yang pernah Anda kirim.</p></div></div>
<div class="table-card"><table><thead><tr><th>Kode transaksi</th><th>Kampanye</th><th>Panti</th><th>Tanggal</th><th>Nominal</th><th>Status</th></tr></thead><tbody>@forelse($donations as $donation)<tr><td><strong>{{ $donation->kode_transaksi }}</strong></td><td>{{ $donation->kampanye->judul }}</td><td>{{ $donation->kampanye->panti->nama }}</td><td>{{ $donation->created_at->format('d M Y') }}</td><td>Rp {{ number_format($donation->nominal,0,',','.') }}<small>{{ $donation->metode }}</small></td><td><span class="status-badge status-{{ $donation->status }}">{{ ucfirst($donation->status) }}</span>@if($donation->catatan_admin)<small>{{ $donation->catatan_admin }}</small>@endif</td></tr>@empty<tr><td colspan="6" class="empty-inline">Belum ada riwayat donasi.</td></tr>@endforelse</tbody></table></div><div class="pagination-wrap">{{ $donations->links() }}</div>
@endsection
