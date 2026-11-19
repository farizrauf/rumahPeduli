<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\Kegiatan;
use App\Models\Panti;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $pantiCount    = Panti::where('status_verifikasi', 'verified')->count();
        $donorCount    = User::where('role', 'donatur')->where('status', 'aktif')->count();
        $donationTotal = Donasi::where('status', 'verified')->sum('nominal');

        $pantiList = Panti::where('status_verifikasi', 'verified')
            ->with('needs')
            ->latest()
            ->take(3)
            ->get();

        $campaigns = Kampanye::where('status', 'aktif')
            ->with('panti')
            ->latest()
            ->take(3)
            ->get();

        $activities = Kegiatan::with('panti')
            ->latest('tanggal')
            ->take(3)
            ->get();

        return view('public.home', compact(
            'pantiCount', 'donorCount', 'donationTotal',
            'pantiList', 'campaigns', 'activities'
        ));
    }

    public function pantiIndex()
    {
        $query = Panti::where('status_verifikasi', 'verified')->with('needs');

        if (request('q')) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . request('q') . '%')
                  ->orWhere('kota', 'like', '%' . request('q') . '%');
            });
        }

        if (request('kota')) {
            $query->where('kota', request('kota'));
        }

        $panti  = $query->latest()->paginate(9)->withQueryString();
        $cities = Panti::where('status_verifikasi', 'verified')->orderBy('kota')->distinct()->pluck('kota');

        return view('public.panti-index', compact('panti', 'cities'));
    }

    public function pantiShow(Panti $panti)
    {
        if ($panti->status_verifikasi !== 'verified') {
            abort(404);
        }

        $panti->load(['needs', 'activities', 'campaigns']);

        return view('public.panti-show', compact('panti'));
    }

    public function campaignIndex()
    {
        $query = Kampanye::where('status', 'aktif')->with('panti');

        if (request('kategori')) {
            $query->where('kategori', request('kategori'));
        }

        $campaigns  = $query->latest()->paginate(9)->withQueryString();
        $categories = Kampanye::where('status', 'aktif')
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('public.campaign-index', compact('campaigns', 'categories'));
    }

    public function campaignShow(Kampanye $kampanye)
    {
        if ($kampanye->status !== 'aktif') {
            abort(404);
        }

        $kampanye->load('panti');

        return view('public.campaign-show', compact('kampanye'));
    }
}
