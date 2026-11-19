<?php

namespace App\Http\Controllers;

use App\Models\Kampanye;
use App\Models\Kebutuhan;
use App\Models\Kegiatan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PantiPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $panti = $request->user()->panti()->with(['needs', 'campaigns', 'expenses'])->firstOrFail();

        return view('panti.dashboard', compact('panti'));
    }

    public function items(Request $request, string $type)
    {
        $allowed = ['kebutuhan', 'kegiatan', 'kampanye', 'pengeluaran'];

        if (! in_array($type, $allowed)) {
            abort(404);
        }

        $panti = $request->user()->panti()->firstOrFail();

        if ($type === 'kebutuhan') {
            $items = $panti->needs()->latest()->paginate(12);
        } elseif ($type === 'kegiatan') {
            $items = $panti->activities()->latest()->paginate(12);
        } elseif ($type === 'kampanye') {
            $items = $panti->campaigns()->latest()->paginate(12);
        } else {
            $items = $panti->expenses()->latest()->paginate(12);
        }

        $campaigns = $panti->campaigns()->get(['id', 'judul']);

        return view('panti.items', compact('panti', 'type', 'items', 'campaigns'));
    }

    public function storeItem(Request $request, string $type, ?int $id = null)
    {
        $allowed = ['kebutuhan', 'kegiatan', 'kampanye', 'pengeluaran'];

        if (! in_array($type, $allowed)) {
            abort(404);
        }

        $panti = $request->user()->panti()->firstOrFail();

        if ($type === 'kebutuhan') {
            $data = $request->validate([
                'nama_barang' => 'required|string|max:150',
                'kategori'    => 'required|string|max:50',
                'jumlah'      => 'required|integer|min:1',
                'prioritas'   => 'required|in:rendah,sedang,tinggi',
                'status'      => 'required|in:dibutuhkan,terpenuhi',
            ]);
            $this->simpan($panti->needs(), $data, $id);

        } elseif ($type === 'kegiatan') {
            $data = $request->validate([
                'judul'     => 'required|string|max:200',
                'deskripsi' => 'nullable|string|max:5000',
                'tanggal'   => 'required|date',
                'foto'      => 'nullable|image|max:5120',
            ]);
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('kegiatan', 'public');
            }
            $this->simpan($panti->activities(), $data, $id);

        } elseif ($type === 'kampanye') {
            if ($panti->status_verifikasi !== 'verified') {
                abort(403, 'Kampanye dapat dibuat setelah panti terverifikasi.');
            }
            $data = $request->validate([
                'judul'           => 'required|string|max:200',
                'deskripsi'       => 'nullable|string|max:5000',
                'kategori'        => 'nullable|string|max:50',
                'target_dana'     => 'required|numeric|min:1',
                'tanggal_mulai'   => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'status'          => 'required|in:draft,aktif',
            ]);
            $this->simpan($panti->campaigns(), $data, $id);

        } else {
            $data = $request->validate([
                'kampanye_id' => 'nullable|integer|exists:kampanye,id',
                'keterangan'  => 'required|string|max:5000',
                'nominal'     => 'required|numeric|min:1',
                'tanggal'     => 'required|date',
                'bukti'       => 'nullable|image|max:5120',
            ]);
            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti-pengeluaran', 'public');
            }
            $this->simpan($panti->expenses(), $data, $id);
        }

        $pesan = $id ? 'Perubahan berhasil disimpan.' : 'Data berhasil disimpan.';

        return back()->with('success', $pesan);
    }

    public function updateProfile(Request $request)
    {
        $panti = $request->user()->panti()->firstOrFail();

        $data = $request->validate([
            'nama'        => 'required|string|max:150',
            'alamat'      => 'required|string|max:3000',
            'kota'        => 'required|string|max:100',
            'deskripsi'   => 'nullable|string|max:5000',
            'jumlah_anak' => 'required|integer|min:0',
            'foto'        => 'nullable|image|max:5120',
            'dokumen'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-panti', 'public');
        }

        if ($request->hasFile('dokumen')) {
            $data['dokumen'] = $request->file('dokumen')->store('dokumen-panti', 'public');
        }

        if ($panti->status_verifikasi === 'rejected') {
            $data['status_verifikasi'] = 'pending';
            $data['catatan_admin']     = null;
        }

        $panti->update($data);

        return back()->with('success', 'Profil panti berhasil diperbarui.');
    }

    public function destroyItem(Request $request, string $type, int $id)
    {
        $panti = $request->user()->panti()->firstOrFail();

        if ($type === 'kebutuhan') {
            $item = Kebutuhan::where('panti_id', $panti->id)->findOrFail($id);
        } elseif ($type === 'kegiatan') {
            $item = Kegiatan::where('panti_id', $panti->id)->findOrFail($id);
        } elseif ($type === 'kampanye') {
            $item = Kampanye::where('panti_id', $panti->id)->findOrFail($id);
        } elseif ($type === 'pengeluaran') {
            $item = Pengeluaran::where('panti_id', $panti->id)->findOrFail($id);
        } else {
            abort(404);
        }

        $item->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    // ─── Private Helper ──────────────────────────────────────────

    private function simpan($relation, array $data, ?int $id): void
    {
        if ($id) {
            $relation->findOrFail($id)->update($data);
            return;
        }

        $relation->create($data);
    }
}
