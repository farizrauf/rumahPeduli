@extends('layouts.dashboard')

@section('title', 'Kelola Panti')

@section('section-name', 'Kelola panti')

@section('dashboard-content')

<div class="dashboard-heading">
    <div>
        <span class="eyebrow">DATA MITRA</span>
        <h1>Kelola panti</h1>
        <p>Tinjau profil dan verifikasi panti yang bergabung.</p>
    </div>
    <span class="status-badge status-pending">{{ $panti->total() }} panti</span>
</div>

<form class="filter-row" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama panti...">
    <select name="status">
        <option value="">Semua status</option>
        @foreach(['pending','verified','rejected'] as $status)
            <option value="{{ $status }}" @selected(request('status')===$status)>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>
    <button class="button button-outline button-small">Filter</button>
</form>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Panti & pengelola</th>
                <th>Lokasi</th>
                <th>Anak</th>
                <th>Dokumen</th>
                <th>Status / verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($panti as $item)
            <tr>
                <td>
                    <strong>{{ $item->nama }}</strong>
                    <small>{{ $item->user->name }} · {{ $item->user->email }}</small>
                </td>
                <td>{{ $item->kota }}</td>
                <td>{{ $item->jumlah_anak }}</td>
                <td>
                    @if($item->dokumen)
                        <a class="table-action" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->dokumen) }}" target="_blank" rel="noopener">
                            Lihat dokumen
                        </a>
                    @else
                        <span class="muted">Belum ada</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $item->status_verifikasi }}">
                        {{ ucfirst($item->status_verifikasi) }}
                    </span>
                    <div class="table-actions-row">
                        @if($item->status_verifikasi !== 'verified')
                            <form class="verify-form" method="POST" action="{{ route('admin.panti.verify',$item) }}">
                                @csrf
                                <input type="hidden" name="status_verifikasi" value="verified">
                                <button class="table-action action-success">Setujui</button>
                            </form>
                        @endif
                        @if($item->status_verifikasi !== 'rejected')
                            <form class="verify-form" method="POST" action="{{ route('admin.panti.verify',$item) }}">
                                @csrf
                                <input type="hidden" name="status_verifikasi" value="rejected">
                                <input name="catatan_admin" placeholder="Catatan bila ditolak">
                                <button class="table-action action-danger">Tolak</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-inline">Belum ada data panti.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $panti->links() }}
</div>

@endsection
