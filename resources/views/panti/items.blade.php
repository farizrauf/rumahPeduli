@extends('layouts.dashboard')

@php($labels = ['kebutuhan' => 'Kebutuhan', 'kegiatan' => 'Kegiatan', 'kampanye' => 'Kampanye', 'pengeluaran' => 'Pengeluaran'])
@php($createFormOld = old('_form_context') === 'create')

@section('title', $labels[$type])
@section('section-name', $labels[$type])

@section('dashboard-content')
<div class="dashboard-heading">
    <div>
        <span class="eyebrow">KELOLA DATA PANTI</span>
        <h1>{{ $labels[$type] }}</h1>
        <p>Tambahkan informasi baru dan pantau status yang telah dikirim.</p>
    </div>
</div>

@if($type === 'kampanye' && $panti->status_verifikasi !== 'verified')
    <div class="notice-card">
        <strong>Kampanye belum dapat dikelola.</strong>
        <p>Verifikasi panti harus disetujui admin sebelum kampanye dapat dibuat atau diubah.</p>
    </div>
@endif

@if($type !== 'kampanye' || $panti->status_verifikasi === 'verified')
<div class="section-heading table-heading panti-form-heading">
    <div>
        <span class="eyebrow">FORM DATA</span>
        <h2>Tambah {{ strtolower($labels[$type]) }}</h2>
    </div>
</div>

<form class="profile-form panti-item-create-form" method="POST" action="{{ route('panti.items.store', $type) }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_form_context" value="create">

    <div class="form-grid">
        @if($type === 'kebutuhan')
            <label>
                Nama kebutuhan
                <input name="nama_barang" value="{{ $createFormOld ? old('nama_barang') : '' }}" required maxlength="150">
            </label>
            <label>
                Kategori
                <input name="kategori" value="{{ $createFormOld ? old('kategori') : '' }}" placeholder="Pangan, Pendidikan..." required maxlength="50">
            </label>
            <label>
                Jumlah unit
                <input type="number" name="jumlah" min="1" value="{{ $createFormOld ? old('jumlah', 1) : 1 }}" required>
            </label>
            <label>
                Prioritas
                <select name="prioritas">
                    @foreach(['rendah', 'sedang', 'tinggi'] as $priority)
                        <option value="{{ $priority }}" @selected(($createFormOld ? old('prioritas', 'sedang') : 'sedang') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Status
                <select name="status">
                    @foreach(['dibutuhkan', 'terpenuhi'] as $status)
                        <option value="{{ $status }}" @selected(($createFormOld ? old('status', 'dibutuhkan') : 'dibutuhkan') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </label>
        @elseif($type === 'kegiatan')
            <label>
                Judul kegiatan
                <input name="judul" value="{{ $createFormOld ? old('judul') : '' }}" required maxlength="200">
            </label>
            <label>
                Tanggal kegiatan
                <input type="date" name="tanggal" value="{{ $createFormOld ? old('tanggal', now()->toDateString()) : now()->toDateString() }}" required>
            </label>
            <label class="span-two">
                Cerita kegiatan
                <textarea name="deskripsi" rows="3" maxlength="5000">{{ $createFormOld ? old('deskripsi') : '' }}</textarea>
            </label>
            <label>
                Foto kegiatan <span class="optional">Opsional · maks. 5 MB</span>
                <input type="file" name="foto" accept="image/*">
            </label>
        @elseif($type === 'kampanye')
            <label>
                Judul kampanye
                <input name="judul" value="{{ $createFormOld ? old('judul') : '' }}" required maxlength="200">
            </label>
            <label>
                Kategori <span class="optional">Opsional</span>
                <input name="kategori" value="{{ $createFormOld ? old('kategori') : '' }}" placeholder="Kesehatan, Pendidikan..." maxlength="50">
            </label>
            <label>
                Target dana (Rp)
                <input type="number" name="target_dana" min="1" value="{{ $createFormOld ? old('target_dana') : '' }}" required>
            </label>
            <label>
                Status awal
                <select name="status">
                    @foreach(['draft', 'aktif'] as $status)
                        <option value="{{ $status }}" @selected(($createFormOld ? old('status', 'draft') : 'draft') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Tanggal mulai
                <input type="date" name="tanggal_mulai" value="{{ $createFormOld ? old('tanggal_mulai', now()->toDateString()) : now()->toDateString() }}" required>
            </label>
            <label>
                Tanggal selesai
                <input type="date" name="tanggal_selesai" value="{{ $createFormOld ? old('tanggal_selesai', now()->addMonth()->toDateString()) : now()->addMonth()->toDateString() }}" required>
            </label>
            <label class="span-two">
                Deskripsi <span class="optional">Opsional</span>
                <textarea name="deskripsi" rows="3" maxlength="5000">{{ $createFormOld ? old('deskripsi') : '' }}</textarea>
            </label>
        @else
            <label>
                Kampanye terkait <span class="optional">Opsional</span>
                <select name="kampanye_id">
                    <option value="">Tanpa kampanye</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" @selected($createFormOld && (string) old('kampanye_id') === (string) $campaign->id)>{{ $campaign->judul }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Nominal (Rp)
                <input type="number" name="nominal" min="1" value="{{ $createFormOld ? old('nominal') : '' }}" required>
            </label>
            <label>
                Tanggal pengeluaran
                <input type="date" name="tanggal" value="{{ $createFormOld ? old('tanggal', now()->toDateString()) : now()->toDateString() }}" required>
            </label>
            <label>
                Bukti pengeluaran <span class="optional">Opsional · maks. 5 MB</span>
                <input type="file" name="bukti" accept="image/*">
            </label>
            <label class="span-two">
                Keterangan
                <textarea name="keterangan" rows="3" maxlength="5000" required>{{ $createFormOld ? old('keterangan') : '' }}</textarea>
            </label>
        @endif
    </div>

    <div class="panti-form-actions">
        <button class="button button-primary" type="submit">Simpan {{ strtolower($labels[$type]) }}</button>
    </div>
</form>
@endif

<div class="section-heading table-heading">
    <div>
        <span class="eyebrow">DATA TERSIMPAN</span>
        <h2>Daftar {{ strtolower($labels[$type]) }}</h2>
    </div>
</div>

<div class="table-card">
    <table>
        <thead>
            @if($type === 'kebutuhan')
                <tr><th>Nama kebutuhan</th><th>Kategori</th><th>Jumlah</th><th>Prioritas</th><th>Status</th><th>Aksi</th></tr>
            @elseif($type === 'kegiatan')
                <tr><th>Kegiatan</th><th>Tanggal</th><th>Deskripsi</th><th>Aksi</th></tr>
            @elseif($type === 'kampanye')
                <tr><th>Kampanye</th><th>Kategori</th><th>Target dana</th><th>Berakhir</th><th>Status</th><th>Aksi</th></tr>
            @else
                <tr><th>Keterangan</th><th>Kampanye</th><th>Tanggal</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr>
            @endif
        </thead>
        <tbody>
            @forelse($items as $item)
                @if($type === 'kebutuhan')
                    <tr>
                        <td><strong>{{ $item->nama_barang }}</strong></td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->jumlah }} unit</td>
                        <td>{{ ucfirst($item->prioritas) }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                @elseif($type === 'kegiatan')
                    <tr>
                        <td><strong>{{ $item->judul }}</strong></td>
                        <td>{{ $item->tanggal->format('d M Y') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '', 70) }}</td>
                @elseif($type === 'kampanye')
                    <tr>
                        <td><strong>{{ $item->judul }}</strong></td>
                        <td>{{ $item->kategori }}</td>
                        <td>Rp {{ number_format($item->target_dana, 0, ',', '.') }}</td>
                        <td>{{ $item->tanggal_selesai->format('d M Y') }}</td>
                        <td><span class="status-badge status-{{ $item->status === 'aktif' ? 'verified' : $item->status }}">{{ ucfirst($item->status) }}</span></td>
                @else
                    <tr>
                        <td><strong>{{ $item->keterangan }}</strong></td>
                        <td>{{ $item->kampanye?->judul ?? '—' }}</td>
                        <td>{{ $item->tanggal->format('d M Y') }}</td>
                        <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        <td><span class="status-badge status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                @endif
                        <td>
                            @if(!($type === 'pengeluaran' && $item->status === 'verified') && !($type === 'kampanye' && $panti->status_verifikasi !== 'verified'))
                                <div class="panti-item-actions">
                                    <button class="table-action" type="button" data-dialog-open="edit-item-{{ $item->id }}" aria-controls="edit-item-{{ $item->id }}">
                                        Ubah
                                    </button>
                                    <form method="POST" action="{{ route('panti.items.destroy', [$type, $item->id]) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="table-action action-danger" type="submit">Hapus</button>
                                    </form>
                                </div>

                                @php($editFormOld = old('_form_context') === 'edit-' . $item->id)
                                <dialog class="item-edit-dialog" id="edit-item-{{ $item->id }}" aria-labelledby="edit-title-{{ $item->id }}" @if($editFormOld) data-reopen @endif>
                                    <form method="POST" action="{{ route('panti.items.update', [$type, $item->id]) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_form_context" value="edit-{{ $item->id }}">
                                        <div class="item-edit-header">
                                            <div>
                                                <span class="eyebrow">PERBARUI DATA</span>
                                                <h2 id="edit-title-{{ $item->id }}">Ubah {{ strtolower($labels[$type]) }}</h2>
                                            </div>
                                            <button class="item-edit-close" type="button" data-dialog-close aria-label="Tutup form edit">×</button>
                                        </div>

                                        @if($editFormOld && $errors->any())
                                            <div class="form-validation-summary" role="alert">
                                                <strong>Periksa kembali data berikut:</strong>
                                                <ul>
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="item-edit-grid">
                                            @if($type === 'kebutuhan')
                                                <label>
                                                    Nama kebutuhan
                                                    <input name="nama_barang" value="{{ $editFormOld ? old('nama_barang', $item->nama_barang) : $item->nama_barang }}" required maxlength="150">
                                                </label>
                                                <label>
                                                    Kategori
                                                    <input name="kategori" value="{{ $editFormOld ? old('kategori', $item->kategori) : $item->kategori }}" required maxlength="50">
                                                </label>
                                                <label>
                                                    Jumlah unit
                                                    <input type="number" name="jumlah" min="1" value="{{ $editFormOld ? old('jumlah', $item->jumlah) : $item->jumlah }}" required>
                                                </label>
                                                <label>
                                                    Prioritas
                                                    <select name="prioritas">
                                                        @foreach(['rendah', 'sedang', 'tinggi'] as $priority)
                                                            <option value="{{ $priority }}" @selected(($editFormOld ? old('prioritas', $item->prioritas) : $item->prioritas) === $priority)>{{ ucfirst($priority) }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label>
                                                    Status
                                                    <select name="status">
                                                        @foreach(['dibutuhkan', 'terpenuhi'] as $status)
                                                            <option value="{{ $status }}" @selected(($editFormOld ? old('status', $item->status) : $item->status) === $status)>{{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                            @elseif($type === 'kegiatan')
                                                <label>
                                                    Judul kegiatan
                                                    <input name="judul" value="{{ $editFormOld ? old('judul', $item->judul) : $item->judul }}" required maxlength="200">
                                                </label>
                                                <label>
                                                    Tanggal kegiatan
                                                    <input type="date" name="tanggal" value="{{ $editFormOld ? old('tanggal', $item->tanggal->toDateString()) : $item->tanggal->toDateString() }}" required>
                                                </label>
                                                <label class="item-edit-field-wide">
                                                    Cerita kegiatan
                                                    <textarea name="deskripsi" rows="3" maxlength="5000">{{ $editFormOld ? old('deskripsi', $item->deskripsi) : $item->deskripsi }}</textarea>
                                                </label>
                                                <label>
                                                    Ganti foto <span class="optional">Opsional · maks. 5 MB</span>
                                                    @if($item->foto)
                                                        <span class="optional">Foto saat ini: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->foto) }}" target="_blank" rel="noopener">Lihat foto</a>.</span>
                                                    @endif
                                                    <input type="file" name="foto" accept="image/*">
                                                </label>
                                            @elseif($type === 'kampanye')
                                                <label>
                                                    Judul kampanye
                                                    <input name="judul" value="{{ $editFormOld ? old('judul', $item->judul) : $item->judul }}" required maxlength="200">
                                                </label>
                                                <label>
                                                    Kategori <span class="optional">Opsional</span>
                                                    <input name="kategori" value="{{ $editFormOld ? old('kategori', $item->kategori) : $item->kategori }}" maxlength="50">
                                                </label>
                                                <label>
                                                    Target dana (Rp)
                                                    <input type="number" name="target_dana" min="1" value="{{ $editFormOld ? old('target_dana', $item->target_dana) : $item->target_dana }}" required>
                                                </label>
                                                <label>
                                                    Status
                                                    <select name="status">
                                                        @foreach(['draft', 'aktif'] as $status)
                                                            <option value="{{ $status }}" @selected(($editFormOld ? old('status', $item->status) : $item->status) === $status)>{{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label>
                                                    Tanggal mulai
                                                    <input type="date" name="tanggal_mulai" value="{{ $editFormOld ? old('tanggal_mulai', $item->tanggal_mulai->toDateString()) : $item->tanggal_mulai->toDateString() }}" required>
                                                </label>
                                                <label>
                                                    Tanggal selesai
                                                    <input type="date" name="tanggal_selesai" value="{{ $editFormOld ? old('tanggal_selesai', $item->tanggal_selesai->toDateString()) : $item->tanggal_selesai->toDateString() }}" required>
                                                </label>
                                                <label class="item-edit-field-wide">
                                                    Deskripsi <span class="optional">Opsional</span>
                                                    <textarea name="deskripsi" rows="3" maxlength="5000">{{ $editFormOld ? old('deskripsi', $item->deskripsi) : $item->deskripsi }}</textarea>
                                                </label>
                                            @else
                                                <label>
                                                    Kampanye terkait <span class="optional">Opsional</span>
                                                    <select name="kampanye_id">
                                                        <option value="">Tanpa kampanye</option>
                                                        @foreach($campaigns as $campaign)
                                                            <option value="{{ $campaign->id }}" @selected((string) ($editFormOld ? old('kampanye_id', $item->kampanye_id) : $item->kampanye_id) === (string) $campaign->id)>{{ $campaign->judul }}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                                <label>
                                                    Nominal (Rp)
                                                    <input type="number" name="nominal" min="1" value="{{ $editFormOld ? old('nominal', $item->nominal) : $item->nominal }}" required>
                                                </label>
                                                <label>
                                                    Tanggal pengeluaran
                                                    <input type="date" name="tanggal" value="{{ $editFormOld ? old('tanggal', $item->tanggal->toDateString()) : $item->tanggal->toDateString() }}" required>
                                                </label>
                                                <label>
                                                    Ganti bukti <span class="optional">Opsional · maks. 5 MB</span>
                                                    @if($item->bukti)
                                                        <span class="optional">Bukti saat ini: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->bukti) }}" target="_blank" rel="noopener">Lihat bukti</a>.</span>
                                                    @endif
                                                    <input type="file" name="bukti" accept="image/*">
                                                </label>
                                                <label class="item-edit-field-wide">
                                                    Keterangan
                                                    <textarea name="keterangan" rows="3" maxlength="5000" required>{{ $editFormOld ? old('keterangan', $item->keterangan) : $item->keterangan }}</textarea>
                                                </label>
                                            @endif
                                        </div>

                                        <div class="item-edit-actions">
                                            <button class="button button-outline button-small" type="button" data-dialog-close>Batal</button>
                                            <button class="button button-primary button-small" type="submit">Simpan perubahan</button>
                                        </div>
                                    </form>
                                </dialog>
                            @else
                                <span class="muted">Terkunci</span>
                            @endif
                        </td>
                    </tr>
            @empty
                <tr><td colspan="6" class="empty-inline">Belum ada data {{ strtolower($labels[$type]) }}.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $items->links() }}</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-dialog-open]').forEach((button) => {
        button.addEventListener('click', () => {
            const dialog = document.getElementById(button.dataset.dialogOpen);
            if (dialog) dialog.showModal();
        });
    });

    document.querySelectorAll('[data-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => button.closest('dialog')?.close());
    });

    document.querySelectorAll('dialog[data-reopen]').forEach((dialog) => dialog.showModal());
</script>
@endpush
