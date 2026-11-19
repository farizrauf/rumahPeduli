<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function create(Kampanye $kampanye)
    {
        abort_unless($kampanye->status === 'aktif' && $kampanye->panti()->where('status_verifikasi', 'verified')->exists(), 404);

        return view('public.donate', [
            'kampanye' => $kampanye,
        ]);
    }

    public function store(Request $request, Kampanye $kampanye)
    {
        abort_unless($kampanye->status === 'aktif' && $kampanye->panti()->where('status_verifikasi', 'verified')->exists(), 404);

        // Alur donasi sengaja dibuat jelas: validasi, simpan, lalu arahkan
        // donatur ke riwayat transaksi.
        $data = $request->validate([
            'nominal' => ['required', 'numeric', 'min:1000', 'max:9999999999999'],
            'metode' => ['required', 'in:Transfer BCA,Transfer Mandiri,QRIS,GoPay'],
            'bukti' => ['nullable', 'image', 'max:5120'],
        ]);

        $buktiPembayaran = null;
        if ($request->hasFile('bukti')) {
            $buktiPembayaran = $request->file('bukti')->store('bukti-donasi', 'public');
        }

        $donasi = DB::transaction(function () use ($request, $kampanye, $data, $buktiPembayaran) {
            return Donasi::create([
                'user_id' => $request->user()->id,
                'kampanye_id' => $kampanye->id,
                'kode_transaksi' => $this->buatKodeTransaksi(),
                'nominal' => $data['nominal'],
                'metode' => $data['metode'],
                'bukti' => $buktiPembayaran,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('donatur.donations')->with('success', "Donasi {$donasi->kode_transaksi} berhasil dicatat dan menunggu verifikasi.");
    }

    /** Menampilkan bukti transaksi milik donatur yang sedang login. */
    public function receipt(Request $request, Donasi $donasi)
    {
        abort_unless($donasi->user_id === $request->user()->id, 403);

        $donasi->load(['user', 'kampanye.panti']);

        return view('donatur.receipt', [
            'donasi' => $donasi,
        ]);
    }

    private function buatKodeTransaksi(): string
    {
        do {
            $kode = 'TRX-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Donasi::where('kode_transaksi', $kode)->exists());

        return $kode;
    }
}
