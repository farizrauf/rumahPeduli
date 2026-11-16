@extends('layouts.dashboard')
@section('title', 'Audit Aktivitas')
@section('section-name', 'Audit aktivitas')
@section('dashboard-content')
<div class="dashboard-heading"><div><span class="eyebrow">CATATAN PERUBAHAN</span><h1>Audit aktivitas</h1><p>Riwayat verifikasi dan perubahan akses yang dilakukan admin.</p></div></div>
<div class="table-card"><table><thead><tr><th>Waktu</th><th>Admin</th><th>Aktivitas</th><th>Data terkait</th><th>Catatan</th></tr></thead><tbody>@forelse($logs as $log)<tr><td>{{ $log->created_at->format('d M Y H:i') }}</td><td>{{ $log->user?->name ?? 'Akun dihapus' }}</td><td><span class="status-badge status-{{ str_ends_with($log->action,'verified')||str_ends_with($log->action,'aktif')?'verified':(str_ends_with($log->action,'rejected')||str_ends_with($log->action,'nonaktif')?'rejected':'pending') }}">{{ str_replace('.', ' · ', $log->action) }}</span></td><td>{{ $log->subject_type }} #{{ $log->subject_id }}</td><td>{{ $log->details ?? '—' }}</td></tr>@empty<tr><td colspan="5" class="empty-inline">Belum ada aktivitas admin yang tercatat.</td></tr>@endforelse</tbody></table></div><div class="pagination-wrap">{{ $logs->links() }}</div>
@endsection
