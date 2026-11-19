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
        // Hanya kampanye aktif dari panti terverifikasi yang bisa didonasi.
        if ($kampanye->status !== 'aktif') {
            abort(404);
        }

        return view('public.donate', compact('kampanye'));
    }

    public function store(Request $request, Kampanye $kampanye)
    {
        if ($kampanye->status !== 'aktif') {
            abort(404);
        }

        $request->validate([
            'nominal' => 'required|numeric|min:1000',
            'metode'  => 'required|in:Transfer BCA,Transfer Mandiri,QRIS,GoPay',
            'bukti'   => 'nullable|image|max:5120',
        ]);

        $bukti = null;
        if ($request->hasFile('bukti')) {
            $bukti = $request->file('bukti')->store('bukti-donasi', 'public');
        }

        $donasi = Donasi::create([
            'user_id'         => auth()->id(),
            'kampanye_id'     => $kampanye->id,
            'kode_transaksi'  => $this->buatKode(),
            'nominal'         => $request->nominal,
            'metode'          => $request->metode,
            'bukti'           => $bukti,
            'status'          => 'pending',
        ]);

        return redirect()->route('donatur.donations')
            ->with('success', 'Donasi ' . $donasi->kode_transaksi . ' berhasil dicatat dan menunggu verifikasi.');
    }

    public function receipt(Request $request, Donasi $donasi)
    {
        if ($donasi->user_id !== auth()->id()) {
            abort(403);
        }

        $donasi->load(['user', 'kampanye.panti']);

        return view('donatur.receipt', compact('donasi'));
    }

    // ─── Private Helper ──────────────────────────────────────────

    private function buatKode(): string
    {
        do {
            $kode = 'TRX-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Donasi::where('kode_transaksi', $kode)->exists());

        return $kode;
    }
}
