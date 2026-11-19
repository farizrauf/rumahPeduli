@extends('layouts.dashboard')

@section('title', 'Pengguna')

@section('section-name', 'Pengguna')

@section('dashboard-content')

<div class="dashboard-heading">
    <div>
        <span class="eyebrow">AKSES PLATFORM</span>
        <h1>Pengguna</h1>
        <p>Kelola akses donatur dan pengelola panti.</p>
    </div>
    <button class="button button-primary" onclick="document.getElementById('createUserModal').style.display='flex'">
        + Tambah pengguna
    </button>
</div>

{{-- Modal Tambah Pengguna --}}
<div id="createUserModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:10000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:32px;max-width:800px;width:90%;max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <h2 style="margin:0;font-size:20px;font-weight:700;color:#1a3c2e;">Tambah Pengguna Baru</h2>
            <button onclick="document.getElementById('createUserModal').style.display='none'" style="background:none;border:none;font-size:24px;cursor:pointer;color:#6b7280;">&times;</button>
        </div>
        <form class="profile-form" method="POST" action="{{ route('admin.users.store') }}" style="padding:0;border:none;">
            @csrf
            <div class="form-grid">
                <label>
                    Nama lengkap
                    <input name="name" required maxlength="100" placeholder="Nama lengkap pengguna">
                </label>
                <label>
                    Email
                    <input type="email" name="email" required maxlength="150" placeholder="email@example.com">
                </label>
                <label>
                    Peran
                    <select name="role">
                        <option value="donatur">Donatur</option>
                        <option value="panti">Pengelola panti</option>
                        <option value="admin">Admin</option>
                    </select>
                </label>
                <label>
                    Status akun
                    <select name="status">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </label>
                <label>
                    Kata sandi
                    <input type="password" name="password" minlength="8" required placeholder="Minimal 8 karakter">
                </label>
                <label>
                    Ulangi kata sandi
                    <input type="password" name="password_confirmation" minlength="8" required placeholder="Ketik ulang kata sandi">
                </label>
                <div class="span-two" style="padding:16px;background:#f0fdf4;border-radius:10px;margin-top:8px;">
                    <strong style="display:block;color:#166534;margin-bottom:8px;font-size:13px;">Isi kolom panti bila peran yang dipilih adalah pengelola panti</strong>
                </div>
                <label>
                    Nama panti
                    <input name="nama_panti" maxlength="150" placeholder="Nama panti (opsional)">
                </label>
                <label>
                    Kota panti
                    <input name="kota" maxlength="100" placeholder="Kota (opsional)">
                </label>
                <label class="span-two">
                    Alamat panti
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap panti (opsional)"></textarea>
                </label>
                <label>
                    Jumlah anak
                    <input type="number" name="jumlah_anak" min="0" value="0" placeholder="0">
                </label>
            </div>
            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <button type="button" onclick="document.getElementById('createUserModal').style.display='none'" class="button button-outline">Batal</button>
                <button type="submit" class="button button-primary">Simpan pengguna</button>
            </div>
        </form>
    </div>
</div>

<form class="filter-row" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama atau email...">
    <select name="role">
        <option value="">Semua peran</option>
        @foreach(['admin','donatur','panti'] as $role)
            <option value="{{ $role }}" @selected(request('role')===$role)>
                {{ ucfirst($role) }}
            </option>
        @endforeach
    </select>
    <button class="button button-outline button-small">Filter</button>
</form>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Peran</th>
                <th>Panti terhubung</th>
                <th>Bergabung</th>
                <th>Status akun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $item)
            <tr>
                <td>
                    <strong>{{ $item->name }}</strong>
                    <small>{{ $item->email }}</small>
                </td>
                <td>{{ ucfirst($item->role) }}</td>
                <td>{{ $item->panti?->nama ?? '—' }}</td>
                <td>{{ $item->created_at->format('d M Y') }}</td>
                <td>
                    <span class="status-badge status-{{ $item->status==='aktif'?'verified':'rejected' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>
                    @if($item->role !== 'admin' && $item->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle',$item) }}" style="display:inline-block">
                            @csrf
                            <button class="table-action {{ $item->status==='aktif'?'action-danger':'action-success' }}" type="submit">
                                {{ $item->status==='aktif'?'Nonaktifkan':'Aktifkan' }}
                            </button>
                        </form>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-inline">Belum ada pengguna.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $users->links() }}
</div>

@endsection
