# Rumah Peduli

Aplikasi Laravel untuk menghubungkan donatur dengan panti asuhan melalui kampanye, donasi, verifikasi, dan laporan penyaluran yang transparan.

## Kebutuhan

- PHP 8.1+
- Composer
- MySQL 8+ (atau SQLite untuk pengembangan dan pengujian)
- Node.js dan npm

## Menjalankan aplikasi

1. Pasang dependensi PHP dan salin konfigurasi lokal:

   ```sh
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. Buat database MySQL `rumah_peduli`, lalu atur `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.

3. Buat tabel dan data contoh:

   ```sh
   php artisan migrate --seed
   php artisan storage:link
   ```

4. Pasang dependensi frontend dan buat aset:

   ```sh
   npm install
   npm run build
   ```

5. Jalankan server:

   ```sh
   php artisan serve
   ```

Untuk kerja frontend dengan hot reload, gunakan `npm run dev` di terminal terpisah.

## Akun demo

Seeder menyediakan akun admin, donatur, dan pengelola panti. Kata sandi semua akun demo adalah `password`.

| Peran | Email |
|---|---|
| Admin | `admin@rumahpeduli.test` |
| Donatur | `budi@example.test` |
| Donatur | `siti@example.test` |
| Pengelola panti terverifikasi | `ratna@harapanibu.test` |
| Pengelola panti menunggu verifikasi | `hasan@cahayakasih.test` |

Gunakan akun demo hanya di lingkungan lokal. Jangan gunakan kata sandi tersebut di produksi.

## Alur utama

- Pengunjung dapat melihat direktori panti terverifikasi, kampanye aktif, dan laporan publik.
- Donatur membuat akun, mengirim donasi beserta metode/bukti pembayaran, lalu memantau status verifikasi.
- Pengelola mendaftarkan panti (status awal `pending`), mengelola profil, kebutuhan, kegiatan, kampanye, dan pengeluaran.
- Admin memverifikasi panti, donasi, dan bukti pengeluaran; perubahan verifikasi dan status pengguna tercatat di audit aktivitas.
- Laporan hanya menjumlahkan donasi dan pengeluaran yang sudah diverifikasi.

## Pengujian

```sh
php artisan test
php vendor/bin/pint --test
npm run build
```
