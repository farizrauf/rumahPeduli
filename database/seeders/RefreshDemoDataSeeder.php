<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\Kebutuhan;
use App\Models\Kegiatan;
use App\Models\Panti;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LogicException;

class RefreshDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            foreach (['audit_log', 'donasi', 'pengeluaran', 'kegiatan', 'kebutuhan', 'kampanye', 'panti'] as $table) {
                DB::table($table)->delete();
            }

            $admin = User::query()
                ->where('role', 'admin')
                ->where('status', 'aktif')
                ->orderBy('id')
                ->first();

            if (!$admin) {
                throw new LogicException('Seeder demo membutuhkan setidaknya satu akun admin aktif.');
            }

            $owners = User::query()
                ->where('role', 'panti')
                ->where('status', 'aktif')
                ->orderBy('id')
                ->take(5)
                ->get();

            foreach ([
                ['name' => 'Raras Puspa', 'email' => 'raras.puspa@demo.rumahpeduli.test'],
                ['name' => 'Genta Wisesa', 'email' => 'genta.wisesa@demo.rumahpeduli.test'],
                ['name' => 'Svara Adinata', 'email' => 'svara.adinata@demo.rumahpeduli.test'],
                ['name' => 'Nadira Sasmaya', 'email' => 'nadira.sasmaya@demo.rumahpeduli.test'],
                ['name' => 'Tara Wening', 'email' => 'tara.wening@demo.rumahpeduli.test'],
            ] as $ownerData) {
                if ($owners->count() >= 5) {
                    break;
                }

                $owners->push($this->getOrCreateActiveUser($ownerData, 'panti'));
            }

            $donors = collect();
            foreach ([
                ['name' => 'Kirana Banyu', 'email' => 'kirana.banyu@demo.rumahpeduli.test'],
                ['name' => 'Aksara Maheswara', 'email' => 'aksara.maheswara@demo.rumahpeduli.test'],
                ['name' => 'Talita Sasmaya', 'email' => 'talita.sasmaya@demo.rumahpeduli.test'],
                ['name' => 'Jalu Pradipta', 'email' => 'jalu.pradipta@demo.rumahpeduli.test'],
                ['name' => 'Niskala Prawira', 'email' => 'niskala.prawira@demo.rumahpeduli.test'],
            ] as $donorData) {
                $donors->push($this->getOrCreateActiveUser($donorData, 'donatur'));
            }

            $pantiData = [
                [
                    'nama' => 'Rumah Lentera Pesisir',
                    'kota' => 'Makassar',
                    'alamat' => 'Jl. Anging Mammiri No. 18',
                    'deskripsi' => 'Rumah pengasuhan yang mendampingi anak-anak pesisir melalui pendidikan dan pemenuhan gizi.',
                    'jumlah_anak' => 34,
                    'kampanye' => 'Paket Pangan Bergizi untuk Anak Asuh',
                    'kategori' => 'Pangan',
                    'kebutuhan' => ['nama_barang' => 'Paket bahan pangan bulanan', 'kategori' => 'Pangan', 'jumlah' => 34],
                    'kegiatan' => 'Kelas memasak sehat bersama relawan',
                    'donasi' => 18500000,
                    'pengeluaran' => 10250000,
                    'metode' => 'Transfer BCA',
                ],
                [
                    'nama' => 'Panti Rumpun Cendekia',
                    'kota' => 'Yogyakarta',
                    'alamat' => 'Jl. Kaliurang Km. 7 No. 22',
                    'deskripsi' => 'Komunitas pengasuhan yang menyediakan ruang belajar dan pendampingan akademik bagi anak.',
                    'jumlah_anak' => 28,
                    'kampanye' => 'Perlengkapan Belajar Semester Baru',
                    'kategori' => 'Pendidikan',
                    'kebutuhan' => ['nama_barang' => 'Paket alat tulis dan buku', 'kategori' => 'Pendidikan', 'jumlah' => 28],
                    'kegiatan' => 'Pendampingan belajar akhir pekan',
                    'donasi' => 13400000,
                    'pengeluaran' => 8040000,
                    'metode' => 'QRIS',
                ],
                [
                    'nama' => 'Rumah Tumbuh Niskala',
                    'kota' => 'Bandung',
                    'alamat' => 'Jl. Cikutra Baru No. 41',
                    'deskripsi' => 'Panti yang berfokus pada lingkungan aman, kesehatan, dan perkembangan karakter anak.',
                    'jumlah_anak' => 31,
                    'kampanye' => 'Perbaikan Ruang Tidur Anak',
                    'kategori' => 'Fasilitas',
                    'kebutuhan' => ['nama_barang' => 'Kasur dan perlengkapan tidur', 'kategori' => 'Fasilitas', 'jumlah' => 12],
                    'kegiatan' => 'Pemeriksaan kesehatan berkala',
                    'donasi' => 9750000,
                    'pengeluaran' => 4875000,
                    'metode' => 'Transfer Mandiri',
                ],
                [
                    'nama' => 'Panti Pelita Sembada',
                    'kota' => 'Semarang',
                    'alamat' => 'Jl. Gajah Raya No. 16',
                    'deskripsi' => 'Lembaga pengasuhan yang membuka akses layanan kesehatan dan kegiatan kreatif bagi anak.',
                    'jumlah_anak' => 39,
                    'kampanye' => 'Layanan Kesehatan dan Pemeriksaan Rutin',
                    'kategori' => 'Kesehatan',
                    'kebutuhan' => ['nama_barang' => 'Perlengkapan pertolongan pertama', 'kategori' => 'Kesehatan', 'jumlah' => 8],
                    'kegiatan' => 'Lokakarya seni dan kerajinan',
                    'donasi' => 22600000,
                    'pengeluaran' => 13560000,
                    'metode' => 'Transfer BNI',
                ],
                [
                    'nama' => 'Rumah Asa Kertajaya',
                    'kota' => 'Surabaya',
                    'alamat' => 'Jl. Kertajaya Indah No. 9',
                    'deskripsi' => 'Rumah pengasuhan yang mengembangkan keterampilan hidup, kreativitas, dan kemandirian anak.',
                    'jumlah_anak' => 26,
                    'kampanye' => 'Dukungan Keterampilan dan Kreativitas',
                    'kategori' => 'Pengembangan Diri',
                    'kebutuhan' => ['nama_barang' => 'Peralatan kegiatan keterampilan', 'kategori' => 'Pengembangan Diri', 'jumlah' => 10],
                    'kegiatan' => 'Pelatihan keterampilan dasar',
                    'donasi' => 11250000,
                    'pengeluaran' => 6750000,
                    'metode' => 'QRIS',
                ],
            ];

            $now = now();

            foreach ($pantiData as $index => $data) {
                $panti = Panti::create([
                    'user_id' => $owners[$index]->id,
                    'nama' => $data['nama'],
                    'alamat' => $data['alamat'],
                    'kota' => $data['kota'],
                    'deskripsi' => $data['deskripsi'],
                    'jumlah_anak' => $data['jumlah_anak'],
                    'status_verifikasi' => 'verified',
                    'verified_by' => $admin->id,
                    'verified_at' => $now->copy()->subDays(20 - $index),
                ]);

                $campaign = Kampanye::create([
                    'panti_id' => $panti->id,
                    'judul' => $data['kampanye'],
                    'deskripsi' => 'Penggalangan dana terverifikasi untuk mendukung kebutuhan anak asuh di ' . $data['nama'] . '.',
                    'kategori' => $data['kategori'],
                    'target_dana' => 30000000,
                    'tanggal_mulai' => $now->copy()->subDays(25)->toDateString(),
                    'tanggal_selesai' => $now->copy()->addMonths(2)->toDateString(),
                    'status' => 'aktif',
                ]);

                Kebutuhan::create([
                    'panti_id' => $panti->id,
                    'nama_barang' => $data['kebutuhan']['nama_barang'],
                    'kategori' => $data['kebutuhan']['kategori'],
                    'jumlah' => $data['kebutuhan']['jumlah'],
                    'prioritas' => 'tinggi',
                    'status' => 'dibutuhkan',
                ]);

                Kegiatan::create([
                    'panti_id' => $panti->id,
                    'judul' => $data['kegiatan'],
                    'deskripsi' => 'Kegiatan pendampingan anak bersama pengasuh dan relawan.',
                    'tanggal' => $now->copy()->subDays(10 - $index)->toDateString(),
                ]);

                Donasi::create([
                    'user_id' => $donors[$index]->id,
                    'kampanye_id' => $campaign->id,
                    'kode_transaksi' => 'TRX-DEMO-' . $now->format('Ymd') . '-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'nominal' => $data['donasi'],
                    'metode' => $data['metode'],
                    'status' => 'verified',
                    'verified_by' => $admin->id,
                    'verified_at' => $now->copy()->subDays(8 - $index),
                ]);

                Pengeluaran::create([
                    'panti_id' => $panti->id,
                    'kampanye_id' => $campaign->id,
                    'keterangan' => 'Realisasi program: ' . $data['kampanye'],
                    'nominal' => $data['pengeluaran'],
                    'tanggal' => $now->copy()->subDays(5 - $index)->toDateString(),
                    'status' => 'verified',
                    'verified_by' => $admin->id,
                    'verified_at' => $now->copy()->subDays(3 - $index),
                ]);

                AuditLog::create([
                    'user_id' => $admin->id,
                    'action' => 'seed.demo_data',
                    'subject_type' => Panti::class,
                    'subject_id' => $panti->id,
                    'details' => 'Data demo terverifikasi untuk ' . $data['nama'] . '.',
                ]);
            }
        });
    }

    private function getOrCreateActiveUser(array $data, string $role): User
    {
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'role' => $role,
                'status' => 'aktif',
            ]
        );

        if ($user->role !== $role || $user->status !== 'aktif') {
            throw new LogicException('Akun demo yang sudah ada harus memiliki role ' . $role . ' dan status aktif.');
        }

        return $user;
    }
}
