<?php

namespace Database\Seeders;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\Kebutuhan;
use App\Models\Kegiatan;
use App\Models\Panti;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = collect([
            ['name' => 'Admin Rumah Peduli', 'email' => 'admin@rumahpeduli.test', 'role' => 'admin'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.test', 'role' => 'donatur'],
            ['name' => 'Siti Rahma', 'email' => 'siti@example.test', 'role' => 'donatur'],
            ['name' => 'Ibu Ratna', 'email' => 'ratna@harapanibu.test', 'role' => 'panti'],
            ['name' => 'Bapak Hasan', 'email' => 'hasan@cahayakasih.test', 'role' => 'panti'],
        ])->mapWithKeys(function (array $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data + ['password' => Hash::make('password'), 'status' => 'aktif']
            );

            return [$data['email'] => $user];
        });

        $panti = Panti::updateOrCreate(
            ['user_id' => $users['ratna@harapanibu.test']->id],
            [
                'nama' => 'Rumah Harapan Ibu',
                'alamat' => 'Jl. Cihampelas No. 12',
                'kota' => 'Bandung',
                'deskripsi' => 'Panti asuhan yang membina anak yatim dan dhuafa sejak 2010.',
                'jumlah_anak' => 45,
                'status_verifikasi' => 'verified',
                'verified_by' => $users['admin@rumahpeduli.test']->id,
                'verified_at' => now()->subDays(16),
            ]
        );

        $pendingPanti = Panti::updateOrCreate(
            ['user_id' => $users['hasan@cahayakasih.test']->id],
            [
                'nama' => 'Panti Cahaya Kasih',
                'alamat' => 'Jl. Pajajaran No. 88',
                'kota' => 'Bogor',
                'deskripsi' => 'Panti asuhan yang mengajukan verifikasi akun.',
                'jumlah_anak' => 30,
                'status_verifikasi' => 'pending',
            ]
        );

        $campaign = Kampanye::updateOrCreate(
            ['panti_id' => $panti->id, 'judul' => 'Paket Gizi untuk 120 Anak Panti'],
            [
                'deskripsi' => 'Penuhi kebutuhan gizi anak panti selama 3 bulan.',
                'kategori' => 'Kesehatan',
                'target_dana' => 100000000,
                'tanggal_mulai' => now()->subMonth()->toDateString(),
                'tanggal_selesai' => now()->addMonth()->toDateString(),
                'status' => 'aktif',
            ]
        );

        Kampanye::updateOrCreate(
            ['panti_id' => $panti->id, 'judul' => 'Beasiswa Anak Panti'],
            [
                'deskripsi' => 'Biaya sekolah anak panti semester genap.',
                'kategori' => 'Pendidikan',
                'target_dana' => 50000000,
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(2)->toDateString(),
                'status' => 'draft',
            ]
        );

        foreach ([
            ['nama_barang' => 'Paket gizi bulanan', 'kategori' => 'Kesehatan', 'jumlah' => 120, 'prioritas' => 'tinggi', 'status' => 'dibutuhkan'],
            ['nama_barang' => 'Buku pelajaran SD', 'kategori' => 'Pendidikan', 'jumlah' => 60, 'prioritas' => 'sedang', 'status' => 'dibutuhkan'],
            ['nama_barang' => 'Seragam sekolah', 'kategori' => 'Sandang', 'jumlah' => 45, 'prioritas' => 'sedang', 'status' => 'terpenuhi'],
        ] as $need) {
            Kebutuhan::updateOrCreate(['panti_id' => $panti->id, 'nama_barang' => $need['nama_barang']], $need);
        }

        Kegiatan::updateOrCreate(
            ['panti_id' => $panti->id, 'judul' => 'Penyaluran Paket Gizi'],
            ['deskripsi' => 'Pembagian paket gizi untuk seluruh anak panti.', 'tanggal' => now()->subDays(18)->toDateString()]
        );

        $donation = Donasi::updateOrCreate(
            ['kode_transaksi' => 'TRX-DEMO-0001'],
            [
                'user_id' => $users['budi@example.test']->id,
                'kampanye_id' => $campaign->id,
                'nominal' => 2500000,
                'metode' => 'Transfer BCA',
                'status' => 'verified',
                'verified_by' => $users['admin@rumahpeduli.test']->id,
                'verified_at' => now()->subDays(15),
            ]
        );

        Donasi::updateOrCreate(
            ['kode_transaksi' => 'TRX-DEMO-0002'],
            [
                'user_id' => $users['siti@example.test']->id,
                'kampanye_id' => $campaign->id,
                'nominal' => 500000,
                'metode' => 'QRIS',
                'status' => 'pending',
            ]
        );

        Pengeluaran::updateOrCreate(
            ['panti_id' => $panti->id, 'keterangan' => 'Pembelian bahan makanan untuk paket gizi'],
            [
                'kampanye_id' => $campaign->id,
                'nominal' => 1800000,
                'tanggal' => now()->subDays(14)->toDateString(),
                'status' => 'verified',
                'verified_by' => $users['admin@rumahpeduli.test']->id,
                'verified_at' => now()->subDays(13),
            ]
        );

        Pengeluaran::updateOrCreate(
            ['panti_id' => $pendingPanti->id, 'keterangan' => 'Pembelian perlengkapan sekolah'],
            [
                'nominal' => 350000,
                'tanggal' => now()->toDateString(),
                'status' => 'pending',
            ]
        );
    }
}
