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
        // Satu user panti hanya boleh melihat data panti miliknya sendiri.
        $panti = $request->user()->panti()->with(['needs', 'campaigns', 'expenses'])->firstOrFail();

        return view('panti.dashboard', [
            'panti' => $panti,
        ]);
    }

    public function items(Request $request, string $type)
    {
        abort_unless(in_array($type, ['kebutuhan', 'kegiatan', 'kampanye', 'pengeluaran'], true), 404);
        $panti = $request->user()->panti()->firstOrFail();

        // Setiap jenis data memakai relasi panti yang berbeda, tetapi bentuk
        // halaman dan cara paginasi tetap sama.
        if ($type === 'kebutuhan') {
            $relation = $panti->needs();
        } elseif ($type === 'kegiatan') {
            $relation = $panti->activities();
        } elseif ($type === 'kampanye') {
            $relation = $panti->campaigns();
        } else {
            $relation = $panti->expenses();
        }

        return view('panti.items', [
            'panti' => $panti,
            'type' => $type,
            'items' => $relation->latest()->paginate(12),
            'campaigns' => $panti->campaigns()->get(['id', 'judul']),
        ]);
    }

    public function storeItem(Request $request, string $type, ?int $id = null)
    {
        abort_unless(in_array($type, ['kebutuhan', 'kegiatan', 'kampanye', 'pengeluaran'], true), 404);
        $panti = $request->user()->panti()->firstOrFail();

        if ($type === 'kebutuhan') {
            $data = $request->validate([
                'nama_barang' => ['required', 'string', 'max:150'],
                'kategori' => ['required', 'string', 'max:50'],
                'jumlah' => ['required', 'integer', 'min:1'],
                'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
                'status' => ['required', 'in:dibutuhkan,terpenuhi'],
            ]);
            $this->saveItem($panti->needs(), $data, $id);
        } elseif ($type === 'kegiatan') {
            $data = $request->validate([
                'judul' => ['required', 'string', 'max:200'],
                'deskripsi' => ['nullable', 'string', 'max:5000'],
                'tanggal' => ['required', 'date'],
                'foto' => ['nullable', 'image', 'max:5120'],
            ]);
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('kegiatan', 'public');
            }
            $this->saveItem($panti->activities(), $data, $id);
        } elseif ($type === 'kampanye') {
            abort_unless($panti->status_verifikasi === 'verified', 403, 'Kampanye dapat dibuat setelah panti terverifikasi.');
            $data = $request->validate([
                'judul' => ['required', 'string', 'max:200'],
                'deskripsi' => ['nullable', 'string', 'max:5000'],
                'kategori' => ['nullable', 'string', 'max:50'],
                'target_dana' => ['required', 'numeric', 'min:1', 'max:9999999999999'],
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
                'status' => ['required', 'in:draft,aktif'],
            ]);
            $this->saveItem($panti->campaigns(), $data, $id);
        } else {
            $data = $request->validate([
                'kampanye_id' => ['nullable', 'integer', 'exists:kampanye,id'],
                'keterangan' => ['required', 'string', 'max:5000'],
                'nominal' => ['required', 'numeric', 'min:1', 'max:9999999999999'],
                'tanggal' => ['required', 'date'],
                'bukti' => ['nullable', 'image', 'max:5120'],
            ]);
            if (! empty($data['kampanye_id'])) {
                abort_unless($panti->campaigns()->whereKey($data['kampanye_id'])->exists(), 403);
            }
            if ($request->hasFile('bukti')) {
                $data['bukti'] = $request->file('bukti')->store('bukti-pengeluaran', 'public');
            }
            $this->saveItem($panti->expenses(), $data, $id);
        }

        $pesan = $id ? 'Perubahan berhasil disimpan.' : 'Data berhasil disimpan.';

        return back()->with('success', $pesan);
    }

    public function updateProfile(Request $request)
    {
        $panti = $request->user()->panti()->firstOrFail();
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string', 'max:3000'],
            'kota' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'jumlah_anak' => ['required', 'integer', 'min:0', 'max:100000'],
            'foto' => ['nullable', 'image', 'max:5120'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
        // File profil disimpan sesuai jenisnya agar mudah ditemukan kembali.
        foreach (['foto', 'dokumen'] as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $request->file($file)->store($file === 'foto' ? 'foto-panti' : 'dokumen-panti', 'public');
            }
        }
        if ($panti->status_verifikasi === 'rejected') {
            $data['status_verifikasi'] = 'pending';
            $data['catatan_admin'] = null;
        }
        $panti->update($data);

        return back()->with('success', 'Profil panti berhasil diperbarui.');
    }

    public function destroyItem(Request $request, string $type, int $id)
    {
        $panti = $request->user()->panti()->firstOrFail();
        if ($type === 'kebutuhan') {
            $model = Kebutuhan::query();
        } elseif ($type === 'kegiatan') {
            $model = Kegiatan::query();
        } elseif ($type === 'kampanye') {
            $model = Kampanye::query();
        } elseif ($type === 'pengeluaran') {
            $model = Pengeluaran::query();
        } else {
            abort(404);
        }

        $item = $model->where('panti_id', $panti->id)->findOrFail($id);
        abort_if($item instanceof Pengeluaran && $item->status === 'verified', 403, 'Pengeluaran yang telah diverifikasi tidak dapat dihapus.');
        abort_if($item instanceof Kampanye && $item->donasi()->exists(), 422, 'Kampanye dengan transaksi donasi tidak dapat dihapus.');
        $item->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    private function saveItem($relation, array $data, ?int $id): void
    {
        // Jika ada id berarti form sedang mengubah data lama.
        if ($id) {
            $item = $relation->findOrFail($id);
            abort_if($item instanceof Pengeluaran && $item->status === 'verified', 403, 'Pengeluaran yang telah diverifikasi tidak dapat diubah.');
            $item->update($data);

            return;
        }

        $relation->create($data);
    }
}
