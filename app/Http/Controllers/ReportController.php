<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Panti;
use App\Models\Pengeluaran;

class ReportController extends Controller
{
    public function index()
    {
        $donasiPerPanti = Donasi::query()
            ->join('kampanye', 'kampanye.id', '=', 'donasi.kampanye_id')
            ->where('donasi.status', 'verified')
            ->groupBy('kampanye.panti_id')
            ->selectRaw('kampanye.panti_id, SUM(donasi.nominal) as total_donasi');

        $pengeluaranPerPanti = Pengeluaran::query()
            ->where('status', 'verified')
            ->groupBy('panti_id')
            ->selectRaw('panti_id, SUM(nominal) as total_pengeluaran');

        $panti = Panti::query()
            ->where('panti.status_verifikasi', 'verified')
            ->leftJoinSub($donasiPerPanti, 'donasi_terverifikasi', function ($join) {
                $join->on('donasi_terverifikasi.panti_id', '=', 'panti.id');
            })
            ->leftJoinSub($pengeluaranPerPanti, 'pengeluaran_terverifikasi', function ($join) {
                $join->on('pengeluaran_terverifikasi.panti_id', '=', 'panti.id');
            })
            ->select('panti.*')
            ->selectRaw('COALESCE(donasi_terverifikasi.total_donasi, 0) as total_donasi')
            ->selectRaw('COALESCE(pengeluaran_terverifikasi.total_pengeluaran, 0) as total_pengeluaran')
            ->paginate(12);

        $totalDonasi      = Donasi::where('status', 'verified')->sum('nominal');
        $totalPengeluaran = Pengeluaran::where('status', 'verified')->sum('nominal');

        return view('public.reports', compact('panti', 'totalDonasi', 'totalPengeluaran'));
    }
}
