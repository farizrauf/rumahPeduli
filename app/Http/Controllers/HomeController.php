<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kegiatan;
use App\Models\Kampanye;
use App\Models\Panti;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Halaman depan hanya menampilkan data yang sudah layak dilihat publik.
        $pantiList = Panti::where('status_verifikasi', 'verified')
            ->with('needs')
            ->latest()
            ->take(3)
            ->get();

        $campaigns = Kampanye::where('status', 'aktif')
            ->whereHas('panti', function ($query) {
                $query->where('status_verifikasi', 'verified');
            })
            ->with('panti')
            ->withSum(['donasi as dana_terkumpul' => function ($query) {
                $query->where('status', 'verified');
            }], 'nominal')
            ->latest()
            ->take(3)
            ->get();

        $activities = Kegiatan::whereHas('panti', function ($query) {
                $query->where('status_verifikasi', 'verified');
            })
            ->with('panti')
            ->latest('tanggal')
            ->take(3)
            ->get();

        return view('public.home', [
            'pantiCount' => Panti::where('status_verifikasi', 'verified')->count(),
            'donorCount' => User::where('role', 'donatur')->where('status', 'aktif')->count(),
            'donationTotal' => Donasi::where('status', 'verified')->sum('nominal'),
            'pantiList' => $pantiList,
            'campaigns' => $campaigns,
            'activities' => $activities,
        ]);
    }

    public function pantiIndex()
    {
        $panti = Panti::where('status_verifikasi', 'verified')
            ->with('needs')
            ->when(request('q'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%");
                });
            })
            ->when(request('kota'), function ($query, $city) {
                $query->where('kota', $city);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('public.panti-index', [
            'panti' => $panti,
            'cities' => Panti::where('status_verifikasi', 'verified')->orderBy('kota')->distinct()->pluck('kota'),
        ]);
    }

    public function pantiShow(Panti $panti)
    {
        abort_unless($panti->status_verifikasi === 'verified', 404);

        $panti->load([
            'needs',
            'activities' => fn ($query) => $query->latest('tanggal')->take(4),
            'campaigns' => fn ($query) => $query->where('status', 'aktif')->latest(),
        ]);

        return view('public.panti-show', [
            'panti' => $panti,
        ]);
    }

    public function campaignIndex()
    {
        $campaigns = Kampanye::where('status', 'aktif')
            ->whereHas('panti', fn ($query) => $query->where('status_verifikasi', 'verified'))
            ->with('panti')
            ->withSum(['donasi as dana_terkumpul' => fn ($query) => $query->where('status', 'verified')], 'nominal')
            ->when(request('kategori'), function ($query, $category) {
                $query->where('kategori', $category);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('public.campaign-index', [
            'campaigns' => $campaigns,
            'categories' => Kampanye::where('status', 'aktif')->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori'),
        ]);
    }

    public function campaignShow(Kampanye $kampanye)
    {
        abort_unless($kampanye->status === 'aktif' && $kampanye->panti()->where('status_verifikasi', 'verified')->exists(), 404);

        $kampanye->load('panti');
        $kampanye->loadSum(['donasi as dana_terkumpul' => fn ($query) => $query->where('status', 'verified')], 'nominal');
        $kampanye->loadCount(['donasi as jumlah_donatur' => fn ($query) => $query->where('status', 'verified')]);

        return view('public.campaign-show', [
            'kampanye' => $kampanye,
        ]);
    }
}
