<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\Panti;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ─── Dashboard ───────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'panti'          => Panti::count(),
            'panti_pending'  => Panti::where('status_verifikasi', 'pending')->count(),
            'donatur'        => User::where('role', 'donatur')->count(),
            'donasi'         => Donasi::where('status', 'verified')->sum('nominal'),
            'donasi_pending' => Donasi::where('status', 'pending')->count(),
            'kampanye'       => Kampanye::where('status', 'aktif')->count(),
        ];

        $pendingPanti     = Panti::where('status_verifikasi', 'pending')->latest()->take(5)->get();
        $pendingDonations = Donasi::with(['user', 'kampanye'])->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'pendingPanti', 'pendingDonations'));
    }

    // ─── Panti ───────────────────────────────────────────────────

    public function panti()
    {
        $query = Panti::with('user');

        if (request('trashed') === 'only') {
            $query->onlyTrashed();
        } elseif (request('trashed') === 'with') {
            $query->withTrashed();
        }

        if (request('status')) {
            $query->where('status_verifikasi', request('status'));
        }

        if (request('q')) {
            $query->where('nama', 'like', '%' . request('q') . '%');
        }

        $panti = $query->latest()->paginate(12)->withQueryString();

        return view('admin.panti', compact('panti'));
    }

    public function verifyPanti(Request $request, Panti $panti)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'catatan_admin'     => 'nullable|string|max:255',
        ]);

        $panti->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_admin'     => $request->catatan_admin,
            'verified_by'       => auth()->id(),
            'verified_at'       => now(),
        ]);

        $this->catat($request, 'panti.' . $request->status_verifikasi, $panti);

        return back()->with('success', 'Status panti berhasil diperbarui.');
    }

    public function destroyPanti(Panti $panti)
    {
        $panti->delete();

        return back()->with('success', 'Panti berhasil diarsipkan.');
    }

    public function restorePanti($id)
    {
        $panti = Panti::withTrashed()->findOrFail($id);
        $panti->restore();

        return back()->with('success', 'Panti berhasil dipulihkan.');
    }

    // ─── Kampanye ────────────────────────────────────────────────

    public function campaigns()
    {
        $campaigns = Kampanye::with('panti')->latest()->paginate(12)->withQueryString();

        return view('admin.campaigns', compact('campaigns'));
    }

    // ─── Donasi ──────────────────────────────────────────────────

    public function donations()
    {
        $donations = Donasi::with(['user', 'kampanye']);

        if (request('status')) {
            $donations->where('status', request('status'));
        }

        $donations = $donations->latest()->paginate(12)->withQueryString();

        return view('admin.donations', compact('donations'));
    }

    public function verifyDonation(Request $request, Donasi $donasi)
    {
        $request->validate([
            'status'        => 'required|in:verified,rejected',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $donasi->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'verified_by'   => auth()->id(),
            'verified_at'   => now(),
        ]);

        $this->catat($request, 'donasi.' . $request->status, $donasi);

        return back()->with('success', 'Status donasi berhasil diperbarui.');
    }

    // ─── Pengeluaran ─────────────────────────────────────────────

    public function expenses()
    {
        $expenses = Pengeluaran::with(['panti', 'kampanye']);

        if (request('status')) {
            $expenses->where('status', request('status'));
        }

        $expenses = $expenses->latest()->paginate(12)->withQueryString();

        return view('admin.expenses', compact('expenses'));
    }

    public function verifyExpense(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'status'        => 'required|in:verified,rejected',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $pengeluaran->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'verified_by'   => auth()->id(),
            'verified_at'   => now(),
        ]);

        $this->catat($request, 'pengeluaran.' . $request->status, $pengeluaran);

        return back()->with('success', 'Status pengeluaran berhasil diperbarui.');
    }

    // ─── Pengguna ────────────────────────────────────────────────

    public function users()
    {
        $users = User::with('panti');

        if (request('role')) {
            $users->where('role', request('role'));
        }

        if (request('q')) {
            $users->where(function ($q) {
                $q->where('name', 'like', '%' . request('q') . '%')
                  ->orWhere('email', 'like', '%' . request('q') . '%');
            });
        }

        $users = $users->latest()->paginate(12)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:150|unique:users,email',
            'password'   => 'required|confirmed|min:8',
            'role'       => 'required|in:admin,donatur,panti',
            'status'     => 'required|in:aktif,nonaktif',
            'nama_panti' => 'required_if:role,panti|nullable|string|max:150',
            'alamat'     => 'required_if:role,panti|nullable|string|max:3000',
            'kota'       => 'required_if:role,panti|nullable|string|max:100',
            'jumlah_anak'=> 'nullable|integer|min:0',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        if ($request->role === 'panti') {
            $user->panti()->create([
                'nama'               => $request->nama_panti,
                'alamat'             => $request->alamat,
                'kota'               => $request->kota,
                'jumlah_anak'        => $request->jumlah_anak ?? 0,
                'status_verifikasi'  => 'pending',
            ]);
        }

        $this->catat($request, 'pengguna.dibuat', $user);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function toggleUser(Request $request, User $user)
    {
        $user->update([
            'status' => $user->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        $this->catat($request, 'pengguna.' . $user->status, $user);

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }

    // ─── Laporan & Audit ─────────────────────────────────────────

    public function reports()
    {
        $totalDonasi      = Donasi::where('status', 'verified')->sum('nominal');
        $totalPengeluaran = Pengeluaran::where('status', 'verified')->sum('nominal');

        // Panti verified dengan total donasi & pengeluaran via join
        $panti = Panti::where('status_verifikasi', 'verified')
            ->leftJoin('kampanye', 'kampanye.panti_id', '=', 'panti.id')
            ->leftJoin('donasi', function ($join) {
                $join->on('donasi.kampanye_id', '=', 'kampanye.id')
                     ->where('donasi.status', 'verified');
            })
            ->leftJoin('pengeluaran', function ($join) {
                $join->on('pengeluaran.panti_id', '=', 'panti.id')
                     ->where('pengeluaran.status', 'verified');
            })
            ->select('panti.*')
            ->selectRaw('COALESCE(SUM(DISTINCT donasi.nominal), 0) as total_donasi')
            ->selectRaw('COALESCE(SUM(DISTINCT pengeluaran.nominal), 0) as total_pengeluaran')
            ->groupBy('panti.id')
            ->paginate(12);

        // Data donasi 6 bulan terakhir
        $monthly = collect(range(5, 0))->map(function ($monthsAgo) {
            $period = now()->startOfMonth()->subMonths($monthsAgo);
            return [
                'periode' => $period->format('Y-m'),
                'total'   => Donasi::where('status', 'verified')
                    ->whereBetween('created_at', [$period, $period->copy()->endOfMonth()])
                    ->sum('nominal'),
            ];
        });

        return view('admin.reports', compact('panti', 'totalDonasi', 'totalPengeluaran', 'monthly'));
    }

    public function audit()
    {
        $logs = AuditLog::with('user')->latest()->paginate(25);

        return view('admin.audit', compact('logs'));
    }

    // ─── Private Helper ──────────────────────────────────────────

    private function catat(Request $request, string $action, $subject, ?string $details = null): void
    {
        AuditLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => class_basename($subject),
            'subject_id'   => $subject->getKey(),
            'details'      => $details,
        ]);
    }
}
