<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Panti;
use App\Models\Pengeluaran;

class ReportController extends Controller
{
    public function index()
    {
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

        return view('public.reports', [
            'panti' => $panti,
            'totalDonasi' => Donasi::where('status', 'verified')->sum('nominal'),
            'totalPengeluaran' => Pengeluaran::where('status', 'verified')->sum('nominal'),
        ]);
    }
}
