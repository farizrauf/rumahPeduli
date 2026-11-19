<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\Panti;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Angka ringkasan diambil dari status terakhir yang sudah diverifikasi.
        return view('admin.dashboard', [
            'stats' => [
                'panti' => Panti::count(),
                'panti_pending' => Panti::where('status_verifikasi', 'pending')->count(),
                'donatur' => User::where('role', 'donatur')->count(),
                'donasi' => Donasi::where('status', 'verified')->sum('nominal'),
                'donasi_pending' => Donasi::where('status', 'pending')->count(),
                'kampanye' => Kampanye::where('status', 'aktif')->count(),
            ],
            'pendingPanti' => Panti::where('status_verifikasi', 'pending')->latest()->take(5)->get(),
            'pendingDonations' => Donasi::with(['user', 'kampanye'])->where('status', 'pending')->latest()->take(5)->get(),
        ]);
    }

    public function panti()
    {
        $query = Panti::with('user');

        // Handle trashed filter
        if (request('trashed') === 'only') {
            $query->onlyTrashed();
        } elseif (request('trashed') === 'with') {
            $query->withTrashed();
        }

        // Other filters
        $panti = $query
            ->when(request('status'), function ($q, $status) {
                $q->where('status_verifikasi', $status);
            })
            ->when(request('q'), function ($q, $search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.panti', [
            'panti' => $panti,
        ]);
    }

    public function campaigns()
    {
        $campaigns = Kampanye::with('panti')
            ->withSum(['donasi as dana_terkumpul' => function ($query) {
                $query->where('status', 'verified');
            }], 'nominal')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.campaigns', [
            'campaigns' => $campaigns,
        ]);
    }

    public function donations()
    {
        $donations = Donasi::with(['user', 'kampanye'])
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.donations', [
            'donations' => $donations,
        ]);
    }

    public function expenses()
    {
        $expenses = Pengeluaran::with(['panti', 'kampanye'])
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.expenses', [
            'expenses' => $expenses,
        ]);
    }

    public function users()
    {
        $users = User::with('panti')
            ->when(request('role'), function ($query, $role) {
                $query->where('role', $role);
            })
            ->when(request('q'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    public function storeUser(Request $request)
    {
        // Admin dapat membuat donatur atau panti. Jika role panti dipilih,
        // profil panti dibuat dalam transaksi yang sama.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:admin,donatur,panti'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'nama_panti' => ['required_if:role,panti', 'nullable', 'string', 'max:150'],
            'alamat' => ['required_if:role,panti', 'nullable', 'string', 'max:3000'],
            'kota' => ['required_if:role,panti', 'nullable', 'string', 'max:100'],
            'jumlah_anak' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'status' => $data['status'],
            ]);

            if ($data['role'] === 'panti') {
                $user->panti()->create([
                    'nama' => $data['nama_panti'],
                    'alamat' => $data['alamat'],
                    'kota' => $data['kota'],
                    'jumlah_anak' => $data['jumlah_anak'] ?? 0,
                    'status_verifikasi' => 'pending',
                ]);
            }

            $this->recordAudit($request, 'pengguna.dibuat', $user, null);
        });

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function reports()
    {
        // Laporan admin memakai sumber data yang sama dengan laporan publik,
        // tetapi ditambah rincian per panti dan grafik enam bulan terakhir.
        $panti = Panti::where('status_verifikasi', 'verified')
            ->select('panti.*')
            ->selectSub(fn ($query) => $query->from('kampanye')
                ->join('donasi', 'kampanye.id', '=', 'donasi.kampanye_id')
                ->whereColumn('kampanye.panti_id', 'panti.id')
                ->where('donasi.status', 'verified')
                ->selectRaw('coalesce(sum(donasi.nominal), 0)'), 'total_donasi')
            ->selectSub(fn ($query) => $query->from('pengeluaran')
                ->whereColumn('pengeluaran.panti_id', 'panti.id')
                ->where('pengeluaran.status', 'verified')
                ->selectRaw('coalesce(sum(pengeluaran.nominal), 0)'), 'total_pengeluaran')
            ->paginate(12);

        return view('admin.reports', [
            'panti' => $panti,
            'totalDonasi' => Donasi::where('status', 'verified')->sum('nominal'),
            'totalPengeluaran' => Pengeluaran::where('status', 'verified')->sum('nominal'),
            'monthly' => collect(range(0, 5))->map(function (int $monthsAgo) {
                $period = now()->startOfMonth()->subMonths($monthsAgo);

                return [
                    'periode' => $period->format('Y-m'),
                    'total' => Donasi::where('status', 'verified')
                        ->where('created_at', '>=', $period)
                        ->where('created_at', '<', $period->copy()->addMonth())
                        ->sum('nominal'),
                ];
            }),
        ]);
    }

    public function audit()
    {
        return view('admin.audit', [
            'logs' => AuditLog::with('user')->latest()->paginate(25),
        ]);
    }

    public function verifyPanti(Request $request, Panti $panti)
    {
        // Verifikasi selalu menyimpan siapa adminnya dan kapan dilakukan.
        $data = $request->validate([
            'status_verifikasi' => ['required', 'in:verified,rejected'],
            'catatan_admin' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data, $panti, $request) {
            $panti->update($data + [
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
            $this->recordAudit($request, 'panti.'.$data['status_verifikasi'], $panti, $data['catatan_admin'] ?? null);
        });

        return back()->with('success', 'Status pengajuan panti berhasil diperbarui.');
    }

    public function destroyPanti(Panti $panti)
    {
        // Soft delete - data tetap ada di database dengan deleted_at
        $panti->delete();

        return back()->with('success', 'Panti berhasil diarsipkan. Data masih tersimpan dan dapat dipulihkan.');
    }

    public function restorePanti($id)
    {
        // Restore soft deleted panti
        $panti = Panti::withTrashed()->findOrFail($id);
        $panti->restore();

        return back()->with('success', 'Panti berhasil dipulihkan dari arsip.');
    }

    public function verifyDonation(Request $request, Donasi $donasi)
    {
        $data = $request->validate([
            'status' => ['required', 'in:verified,rejected'],
            'catatan_admin' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data, $donasi, $request) {
            $donasi->update($data + [
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
            $this->recordAudit($request, 'donasi.'.$data['status'], $donasi, $data['catatan_admin'] ?? null);
        });

        return back()->with('success', 'Status donasi berhasil diperbarui.');
    }

    public function verifyExpense(Request $request, Pengeluaran $pengeluaran)
    {
        $data = $request->validate([
            'status' => ['required', 'in:verified,rejected'],
            'catatan_admin' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data, $pengeluaran, $request) {
            $pengeluaran->update($data + [
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
            $this->recordAudit($request, 'pengeluaran.'.$data['status'], $pengeluaran, $data['catatan_admin'] ?? null);
        });

        return back()->with('success', 'Status pengeluaran berhasil diperbarui.');
    }

    public function toggleUser(Request $request, User $user)
    {
        abort_if($user->is(auth()->user()), 422, 'Status akun sendiri tidak dapat diubah.');
        abort_if($user->role === 'admin', 403, 'Status akun admin tidak dapat diubah melalui menu ini.');

        DB::transaction(function () use ($user, $request) {
            $user->update(['status' => $user->status === 'aktif' ? 'nonaktif' : 'aktif']);
            $this->recordAudit($request, 'pengguna.'.$user->status, $user, null);
        });

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    private function recordAudit(Request $request, string $action, $subject, ?string $details): void
    {
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'subject_type' => class_basename($subject),
            'subject_id' => $subject->getKey(),
            'details' => $details,
        ]);
    }
}
