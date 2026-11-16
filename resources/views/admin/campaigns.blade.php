@extends('layouts.dashboard')
@section('title', 'Kampanye')
@section('section-name', 'Kampanye')
@section('dashboard-content')
<div class="dashboard-heading"><div><span class="eyebrow">PENGGALANGAN DANA</span><h1>Kampanye</h1><p>Pantau status publikasi dan perkembangan target kampanye.</p></div></div>
<div class="table-card"><table><thead><tr><th>Kampanye</th><th>Panti</th><th>Kategori</th><th>Target dana</th><th>Terkumpul</th><th>Tenggat</th><th>Status</th></tr></thead><tbody>@forelse($campaigns as $item)<tr><td><strong>{{ $item->judul }}</strong><small>{{ $item->created_at->format('d M Y') }}</small></td><td>{{ $item->panti->nama }}</td><td>{{ $item->kategori }}</td><td>Rp {{ number_format($item->target_dana,0,',','.') }}</td><td>Rp {{ number_format($item->dana_terkumpul ?? 0,0,',','.') }}</td><td>{{ $item->tanggal_selesai->format('d M Y') }}</td><td><span class="status-badge status-{{ $item->status==='aktif'?'verified':$item->status }}">{{ ucfirst($item->status) }}</span></td></tr>@empty<tr><td colspan="7" class="empty-inline">Belum ada kampanye.</td></tr>@endforelse</tbody></table></div><div class="pagination-wrap">{{ $campaigns->links() }}</div>
@endsection
