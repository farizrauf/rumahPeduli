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
    <select name="status" aria-label="Filter donasi berdasarkan status">
        <option value="">Semua status</option>
        @foreach(['pending','verified','rejected'] as $status)
            <option value="{{ $status }}" @selected(request('status')===$status)>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>
    <button class="button button-outline button-small" type="submit">Filter</button>
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
                        <a class="table-action" href="{{ route('admin.donations.proof', $item) }}" target="_blank" rel="noopener">
                            Lihat bukti
                        </a>
                    @else
                        <span class="muted">Tidak dilampirkan</span>
                    @endif
                </td>
                <td class="review-action-cell">
                    <div class="verification-status-stack">
                        <span class="status-badge status-{{ $item->status }}">
                            {{ ucfirst($item->status) }}
                        </span>
                        @if($item->status==='pending')
                            <div class="table-actions-row">
                                <form class="verify-form" method="POST" action="{{ route('admin.donations.verify',$item) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button class="table-action action-success" type="submit">Verifikasi</button>
                                </form>
                                <details class="review-reject-details">
                                    <summary class="table-action action-danger">Tolak</summary>
                                    <form class="review-reject-form" method="POST" action="{{ route('admin.donations.verify',$item) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <label for="donation-reason-{{ $item->id }}">Alasan penolakan <span>(opsional)</span></label>
                                        <input id="donation-reason-{{ $item->id }}" name="catatan_admin" maxlength="255" placeholder="Tulis alasan">
                                        <button class="table-action action-danger" type="submit">Konfirmasi penolakan</button>
                                    </form>
                                </details>
                            </div>
                        @elseif($item->catatan_admin)
                            <small style="color:#6b7280;">{{ $item->catatan_admin }}</small>
                        @endif
                    </div>
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
