@extends('layouts.dashboard')

@section('title', 'Donasi')

@section('section-name', 'Donasi')

@section('dashboard-content')

<div class="dashboard-heading">
    <div>
        <span class="eyebrow">VERIFIKASI TRANSAKSI</span>
        <h1>Donasi</h1>
        <p>Tinjau nominal, metode, dan bukti pembayaran yang dikirim donatur.</p>
    </div>
</div>

<form class="filter-row" method="GET">
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
                <th>Transaksi</th>
                <th>Donatur</th>
                <th>Kampanye</th>
                <th>Nominal & metode</th>
                <th>Bukti</th>
                <th>Status / tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $item)
            <tr>
                <td>
                    <strong>{{ $item->kode_transaksi }}</strong>
                    <small>{{ $item->created_at->format('d M Y H:i') }}</small>
                </td>
                <td>
                    {{ $item->user->name }}
                    <small>{{ $item->user->email }}</small>
                </td>
                <td>{{ $item->kampanye->judul }}</td>
                <td>
                    Rp {{ number_format($item->nominal,0,',','.') }}
                    <small>{{ $item->metode }}</small>
                </td>
                <td>
                    @if($item->bukti)
                        <a class="table-action" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->bukti) }}" target="_blank" rel="noopener">
                            Lihat bukti
                        </a>
                    @else
                        <span class="muted">Tidak dilampirkan</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </span>
                    @if($item->status==='pending')
                        <div class="table-actions-row">
                            <form class="verify-form" method="POST" action="{{ route('admin.donations.verify',$item) }}">
                                @csrf
                                <input type="hidden" name="status" value="verified">
                                <button class="table-action action-success">Verifikasi</button>
                            </form>
                            <form class="verify-form" method="POST" action="{{ route('admin.donations.verify',$item) }}">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <input name="catatan_admin" placeholder="Alasan penolakan">
                                <button class="table-action action-danger">Tolak</button>
                            </form>
                        </div>
                    @elseif($item->catatan_admin)
                        <small>{{ $item->catatan_admin }}</small>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-inline">Belum ada transaksi donasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $donations->links() }}
</div>

@endsection
