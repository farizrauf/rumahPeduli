@extends('layouts.dashboard')
@php($labels = ['kebutuhan' => 'Kebutuhan', 'kegiatan' => 'Kegiatan', 'kampanye' => 'Kampanye', 'pengeluaran' => 'Pengeluaran'])
@section('title', $labels[$type])
@section('section-name', $labels[$type])
@section('dashboard-content')
<div class="dashboard-heading"><div><span class="eyebrow">KELOLA DATA PANTI</span><h1>{{ $labels[$type] }}</h1><p>Tambahkan informasi baru dan pantau status yang telah dikirim.</p></div></div>
@if($type === 'kampanye' && $panti->status_verifikasi !== 'verified')<div class="notice-card"><strong>Kampanye belum dapat dipublikasikan.</strong><p>Verifikasi panti harus disetujui admin sebelum kampanye dapat dibuat.</p></div>@endif
<form class="profile-form" method="POST" action="{{ route('panti.items.store', $type) }}" enctype="multipart/form-data">@csrf
    @if($type === 'kebutuhan')
        <div class="form-grid"><label>Nama kebutuhan<input name="nama_barang" value="{{ old('nama_barang') }}" required maxlength="150"></label><label>Kategori<input name="kategori" value="{{ old('kategori') }}" placeholder="Pangan, Pendidikan..." required></label><label>Jumlah unit<input type="number" name="jumlah" min="1" value="{{ old('jumlah',1) }}" required></label><label>Prioritas<select name="prioritas"><option value="rendah">Rendah</option><option value="sedang" selected>Sedang</option><option value="tinggi">Tinggi</option></select></label><label>Status<select name="status"><option value="dibutuhkan">Dibutuhkan</option><option value="terpenuhi">Terpenuhi</option></select></label></div>
    @elseif($type === 'kegiatan')
        <div class="form-grid"><label>Judul kegiatan<input name="judul" required maxlength="200"></label><label>Tanggal kegiatan<input type="date" name="tanggal" value="{{ now()->toDateString() }}" required></label><label class="span-two">Cerita kegiatan<textarea name="deskripsi" rows="3"></textarea></label><label>Foto kegiatan<input type="file" name="foto" accept="image/*"></label></div>
    @elseif($type === 'kampanye')
        <div class="form-grid"><label>Judul kampanye<input name="judul" required maxlength="200"></label><label>Kategori<input name="kategori" maxlength="50" placeholder="Kesehatan, Pendidikan..."></label><label>Target dana (Rp)<input type="number" name="target_dana" min="1" required></label><label>Status awal<select name="status"><option value="draft">Draft</option><option value="aktif">Aktif (publikasikan)</option></select></label><label>Tanggal mulai<input type="date" name="tanggal_mulai" value="{{ now()->toDateString() }}" required></label><label>Tanggal selesai<input type="date" name="tanggal_selesai" value="{{ now()->addMonth()->toDateString() }}" required></label><label class="span-two">Deskripsi<textarea name="deskripsi" rows="3"></textarea></label></div>
    @else
        <div class="form-grid"><label>Kampanye terkait<select name="kampanye_id"><option value="">Tanpa kampanye</option>@foreach($campaigns as $campaign)<option value="{{ $campaign->id }}">{{ $campaign->judul }}</option>@endforeach</select></label><label>Nominal (Rp)<input type="number" name="nominal" min="1" required></label><label>Tanggal pengeluaran<input type="date" name="tanggal" value="{{ now()->toDateString() }}" required></label><label>Bukti pengeluaran<input type="file" name="bukti" accept="image/*"></label><label class="span-two">Keterangan<textarea name="keterangan" rows="3" required></textarea></label></div>
    @endif
    <button class="button button-primary" type="submit">Simpan {{ strtolower($labels[$type]) }}</button>
</form>
<div class="section-heading table-heading"><div><span class="eyebrow">DATA TERSIMPAN</span><h2>Daftar {{ strtolower($labels[$type]) }}</h2></div></div>
<div class="table-card"><table><thead>
    @if($type === 'kebutuhan')<tr><th>Nama kebutuhan</th><th>Kategori</th><th>Jumlah</th><th>Prioritas</th><th>Status</th><th>Aksi</th></tr>
    @elseif($type === 'kegiatan')<tr><th>Kegiatan</th><th>Tanggal</th><th>Deskripsi</th><th>Aksi</th></tr>
    @elseif($type === 'kampanye')<tr><th>Kampanye</th><th>Kategori</th><th>Target dana</th><th>Berakhir</th><th>Status</th><th>Aksi</th></tr>
    @else<tr><th>Keterangan</th><th>Kampanye</th><th>Tanggal</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr>@endif
</thead><tbody>
@forelse($items as $item)
    @if($type === 'kebutuhan')<tr><td><strong>{{ $item->nama_barang }}</strong></td><td>{{ $item->kategori }}</td><td>{{ $item->jumlah }} unit</td><td>{{ ucfirst($item->prioritas) }}</td><td>{{ ucfirst($item->status) }}</td>
    @elseif($type === 'kegiatan')<tr><td><strong>{{ $item->judul }}</strong></td><td>{{ $item->tanggal->format('d M Y') }}</td><td>{{ \Illuminate\Support\Str::limit($item->deskripsi,70) }}</td>
    @elseif($type === 'kampanye')<tr><td><strong>{{ $item->judul }}</strong></td><td>{{ $item->kategori }}</td><td>Rp {{ number_format($item->target_dana,0,',','.') }}</td><td>{{ $item->tanggal_selesai->format('d M Y') }}</td><td><span class="status-badge status-{{ $item->status === 'aktif' ? 'verified' : $item->status }}">{{ ucfirst($item->status) }}</span>
    @else<tr><td><strong>{{ $item->keterangan }}</strong></td><td>{{ $item->kampanye?->judul ?? '—' }}</td><td>{{ $item->tanggal->format('d M Y') }}</td><td>Rp {{ number_format($item->nominal,0,',','.') }}</td><td><span class="status-badge status-{{ $item->status }}">{{ ucfirst($item->status) }}</span>@endif
    <td>@if(!($type === 'pengeluaran' && $item->status === 'verified'))
        <details class="edit-details"><summary class="table-action">Ubah</summary><form method="POST" action="{{ route('panti.items.update', [$type,$item->id]) }}" enctype="multipart/form-data">@csrf @method('PUT')
            @if($type === 'kebutuhan')
                <label>Nama kebutuhan<input name="nama_barang" value="{{ $item->nama_barang }}" required></label><label>Kategori<input name="kategori" value="{{ $item->kategori }}" required></label><label>Jumlah<input type="number" name="jumlah" min="1" value="{{ $item->jumlah }}" required></label><label>Prioritas<select name="prioritas">@foreach(['rendah','sedang','tinggi'] as $priority)<option value="{{ $priority }}" @selected($item->prioritas===$priority)>{{ ucfirst($priority) }}</option>@endforeach</select></label><label>Status<select name="status">@foreach(['dibutuhkan','terpenuhi'] as $status)<option value="{{ $status }}" @selected($item->status===$status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
            @elseif($type === 'kegiatan')
                <label>Judul<input name="judul" value="{{ $item->judul }}" required></label><label>Tanggal<input type="date" name="tanggal" value="{{ $item->tanggal->toDateString() }}" required></label><label>Deskripsi<textarea name="deskripsi" rows="2">{{ $item->deskripsi }}</textarea></label><label>Ganti foto<input type="file" name="foto" accept="image/*"></label>
            @elseif($type === 'kampanye')
                <label>Judul<input name="judul" value="{{ $item->judul }}" required></label><label>Kategori<input name="kategori" value="{{ $item->kategori }}"></label><label>Target dana<input type="number" name="target_dana" min="1" value="{{ $item->target_dana }}" required></label><label>Status<select name="status">@foreach(['draft','aktif'] as $status)<option value="{{ $status }}" @selected($item->status===$status)>{{ ucfirst($status) }}</option>@endforeach</select></label><label>Tanggal mulai<input type="date" name="tanggal_mulai" value="{{ $item->tanggal_mulai->toDateString() }}" required></label><label>Tanggal selesai<input type="date" name="tanggal_selesai" value="{{ $item->tanggal_selesai->toDateString() }}" required></label><label>Deskripsi<textarea name="deskripsi" rows="2">{{ $item->deskripsi }}</textarea></label>
            @else
                <label>Kampanye<select name="kampanye_id"><option value="">Tanpa kampanye</option>@foreach($campaigns as $campaign)<option value="{{ $campaign->id }}" @selected($item->kampanye_id===$campaign->id)>{{ $campaign->judul }}</option>@endforeach</select></label><label>Nominal<input type="number" name="nominal" min="1" value="{{ $item->nominal }}" required></label><label>Tanggal<input type="date" name="tanggal" value="{{ $item->tanggal->toDateString() }}" required></label><label>Keterangan<textarea name="keterangan" rows="2" required>{{ $item->keterangan }}</textarea></label><label>Ganti bukti<input type="file" name="bukti" accept="image/*"></label>
            @endif
            <button class="button button-primary button-small" type="submit">Simpan perubahan</button>
        </form></details>
        <form method="POST" action="{{ route('panti.items.destroy', [$type,$item->id]) }}" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="table-action action-danger" type="submit">Hapus</button></form>
    @else<span class="muted">Terkunci</span>@endif</td></tr>
@empty<tr><td colspan="6" class="empty-inline">Belum ada data {{ strtolower($labels[$type]) }}.</td></tr>@endforelse
</tbody></table></div><div class="pagination-wrap">{{ $items->links() }}</div>
@endsection
