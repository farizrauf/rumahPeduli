<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function create(Kampanye $kampanye)
    {
        abort_unless($kampanye->status === 'aktif' && $kampanye->panti()->where('status_verifikasi', 'verified')->exists(), 404);

        return view('public.donate', compact('kampanye'));
    }

    public function store(Request $request, Kampanye $kampanye)
    {
        abort_unless($kampanye->status === 'aktif' && $kampanye->panti()->where('status_verifikasi', 'verified')->exists(), 404);

        $validated = $request->validate([
            'nominal' => ['required', 'numeric', 'min:1000', 'max:9999999999999'],
            'metode' => ['required', 'in:Transfer BCA,Transfer Mandiri,QRIS,GoPay'],
            'bukti' => ['nullable', 'image', 'max:5120'],
        ]);

        $donasi = Donasi::create([
            'user_id' => $request->user()->id,
            'kampanye_id' => $kampanye->id,
            'kode_transaksi' => 'TRX-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'nominal' => $validated['nominal'],
            'metode' => $validated['metode'],
            'bukti' => $request->file('bukti')?->store('bukti-donasi', 'public'),
            'status' => 'pending',
        ]);

        return redirect()->route('donatur.donations')->with('success', "Donasi {$donasi->kode_transaksi} berhasil dicatat dan menunggu verifikasi.");
    }
}
