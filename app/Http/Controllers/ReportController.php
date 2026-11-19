<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Panti;
use App\Models\Pengeluaran;

class ReportController extends Controller
{
    public function index()
    {
        $panti            = Panti::where('status_verifikasi', 'verified')->paginate(12);
        $totalDonasi      = Donasi::where('status', 'verified')->sum('nominal');
        $totalPengeluaran = Pengeluaran::where('status', 'verified')->sum('nominal');

        return view('public.reports', compact('panti', 'totalDonasi', 'totalPengeluaran'));
    }
}
