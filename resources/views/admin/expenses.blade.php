@extends('layouts.dashboard')

@section('title', 'Pengeluaran')

@section('section-name', 'Pengeluaran')

@section('dashboard-content')

<div class="dashboard-heading">
    <div>
        <span class="eyebrow">BUKTI PENYALURAN</span>
        <h1>Pengeluaran panti</h1>
        <p>Periksa bukti penyaluran sebelum dimasukkan ke laporan publik.</p>
    </div>
</div>

<form class="filter-row" method="GET">
    <select name="status" aria-label="Filter pengeluaran berdasarkan status">
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
                <th>Panti & keterangan</th>
                <th>Kampanye</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Bukti</th>
                <th>Status / tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $item)
            <tr>
                <td>
                    <strong>{{ $item->panti->nama }}</strong>
                    <small>{{ $item->keterangan }}</small>
                </td>
                <td>{{ $item->kampanye?->judul ?? '—' }}</td>
                <td>{{ $item->tanggal->format('d M Y') }}</td>
                <td>Rp {{ number_format($item->nominal,0,',','.') }}</td>
                <td>
                    @if($item->bukti)
                        <a class="table-action" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->bukti) }}" target="_blank" rel="noopener">
                            Lihat bukti
                        </a>
                    @else
                        <span class="muted">Belum ada</span>
                    @endif
                </td>
                <td class="review-action-cell">
                    <div class="verification-status-stack">
                        <span class="status-badge status-{{ $item->status }}">
                            {{ ucfirst($item->status) }}
                        </span>
                        @if($item->status==='pending')
                            <div class="table-actions-row">
                                <form class="verify-form" method="POST" action="{{ route('admin.expenses.verify',$item) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button class="table-action action-success" type="submit">Verifikasi</button>
                                </form>
                                <details class="review-reject-details">
                                    <summary class="table-action action-danger">Tolak</summary>
                                    <form class="review-reject-form" method="POST" action="{{ route('admin.expenses.verify',$item) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <label for="expense-reason-{{ $item->id }}">Alasan penolakan <span>(opsional)</span></label>
                                        <input id="expense-reason-{{ $item->id }}" name="catatan_admin" maxlength="255" placeholder="Tulis alasan">
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
                <td colspan="6" class="empty-inline">Belum ada pengeluaran yang dicatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $expenses->links() }}
</div>

@endsection
