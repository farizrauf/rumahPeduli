@extends('layouts.dashboard')

@section('title', 'Pengguna')

@section('section-name', 'Pengguna')

@section('dashboard-content')
@php($userFormOld = old('_form_context') === 'create-user')

<div class="dashboard-heading">
    <div>
        <span class="eyebrow">AKSES PLATFORM</span>
        <h1>Pengguna</h1>
        <p>Kelola akses donatur dan pengelola panti.</p>
    </div>
    <button class="button button-primary" type="button" data-user-dialog-open>
        + Tambah pengguna
    </button>
</div>

<dialog id="createUserDialog" class="user-create-dialog" aria-labelledby="create-user-title" @if($userFormOld) data-reopen @endif>
    <form class="user-create-form" method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <input type="hidden" name="_form_context" value="create-user">
            <div class="user-create-header">
                <div>
                    <span class="eyebrow">AKSES PLATFORM</span>
                    <h2 id="create-user-title">Tambah pengguna</h2>
                    <p>Buat akun baru untuk donatur, pengelola panti, atau admin.</p>
                </div>
                <button class="item-edit-close" type="button" data-user-dialog-close aria-label="Tutup form tambah pengguna">×</button>
            </div>
            @if($userFormOld && $errors->any())
                <div class="form-validation-summary" role="alert">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="user-create-grid">
                <label>
                    Nama lengkap
                    <input name="name" value="{{ $userFormOld ? old('name') : '' }}" required maxlength="100" autocomplete="name" placeholder="Nama lengkap pengguna">
                </label>
                <label>
                    Email
                    <input type="email" name="email" value="{{ $userFormOld ? old('email') : '' }}" required maxlength="150" autocomplete="email" placeholder="email@example.com">
                </label>
                <label>
                    Peran
                    <select name="role" id="admin-user-role" required>
                        <option value="donatur" @selected(($userFormOld ? old('role', 'donatur') : 'donatur') === 'donatur')>Donatur</option>
                        <option value="panti" @selected(($userFormOld ? old('role', 'donatur') : 'donatur') === 'panti')>Pengelola panti</option>
                        <option value="admin" @selected(($userFormOld ? old('role', 'donatur') : 'donatur') === 'admin')>Admin</option>
                    </select>
                </label>
                <label>
                    Status akun
                    <select name="status" required>
                        <option value="aktif" @selected(($userFormOld ? old('status', 'aktif') : 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(($userFormOld ? old('status', 'aktif') : 'aktif') === 'nonaktif')>Nonaktif</option>
                    </select>
                </label>
                <label>
                    Kata sandi
                    <input type="password" name="password" minlength="8" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                </label>
                <label>
                    Ulangi kata sandi
                    <input type="password" name="password_confirmation" minlength="8" required autocomplete="new-password" placeholder="Ketik ulang kata sandi">
                </label>
            </div>

            <fieldset class="user-panti-fields" id="admin-user-panti-fields" @if(!$userFormOld || old('role') !== 'panti') hidden @endif>
                <legend>Profil panti</legend>
                <p>Lengkapi data ini untuk membuat akun pengelola panti.</p>
                <div class="user-create-grid">
                    <label>
                        Nama panti
                        <input name="nama_panti" value="{{ $userFormOld ? old('nama_panti') : '' }}" maxlength="150" data-panti-required placeholder="Nama resmi panti">
                    </label>
                    <label>
                        Kota
                        <input name="kota" value="{{ $userFormOld ? old('kota') : '' }}" maxlength="100" data-panti-required placeholder="Kota">
                    </label>
                    <label class="user-create-field-wide">
                        Alamat panti
                        <textarea name="alamat" rows="3" maxlength="3000" data-panti-required placeholder="Alamat lengkap panti">{{ $userFormOld ? old('alamat') : '' }}</textarea>
                    </label>
                    <label>
                        Jumlah anak binaan
                        <input type="number" name="jumlah_anak" min="0" value="{{ $userFormOld ? old('jumlah_anak', 0) : 0 }}">
                    </label>
                </div>
            </fieldset>

            <div class="user-create-actions">
                <button type="button" class="button button-outline button-small" data-user-dialog-close>Batal</button>
                <button type="submit" class="button button-primary button-small">Simpan pengguna</button>
            </div>
    </form>
</dialog>

<form class="filter-row" method="GET">
    <input type="search" name="q" value="{{ request('q') }}" aria-label="Cari nama atau email" placeholder="Cari nama atau email...">
    <select name="role" aria-label="Filter berdasarkan peran">
        <option value="">Semua peran</option>
        @foreach(['admin','donatur','panti'] as $role)
            <option value="{{ $role }}" @selected(request('role')===$role)>
                {{ ucfirst($role) }}
            </option>
        @endforeach
    </select>
    <button class="button button-outline button-small" type="submit">Filter</button>
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

@push('scripts')
<script>
    const userDialog = document.getElementById('createUserDialog');
    const userRole = document.getElementById('admin-user-role');
    const pantiFields = document.getElementById('admin-user-panti-fields');

    function updatePantiFields() {
        const isPanti = userRole.value === 'panti';
        pantiFields.hidden = !isPanti;
        pantiFields.querySelectorAll('[data-panti-required]').forEach((input) => {
            input.required = isPanti;
        });
    }

    document.querySelector('[data-user-dialog-open]').addEventListener('click', () => userDialog.showModal());
    document.querySelectorAll('[data-user-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => userDialog.close());
    });
    userDialog.addEventListener('click', (event) => {
        if (event.target === userDialog) userDialog.close();
    });
    userRole.addEventListener('change', updatePantiFields);
    updatePantiFields();

    if (userDialog.hasAttribute('data-reopen')) userDialog.showModal();
</script>
@endpush
