@extends('layouts.app')
@section('title', 'Syarat dan Ketentuan')
@section('content')

<section class="section" style="padding-top:80px;">
    <div class="container" style="max-width:900px;">
        <div style="text-align:center;margin-bottom:48px;">
            <span class="eyebrow" style="justify-content:center;">
                <span class="eyebrow-line"></span>
                KEBIJAKAN PLATFORM
                <span class="eyebrow-line"></span>
            </span>
            <h1 style="font-size:42px;margin:16px 0 12px;">Syarat dan Ketentuan</h1>
            <p style="font-size:16px;color:#6b7280;">Berlaku efektif sejak 6 November 2026</p>
        </div>

        <div style="background:#fff;border:1px solid var(--line);border-radius:20px;padding:48px;">
            
            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">1. Ketentuan Umum</h2>
                <p style="line-height:1.8;color:#374151;margin-bottom:12px;">
                    Dengan mengakses dan menggunakan platform Rumah Peduli, Anda menyetujui untuk terikat dengan syarat dan ketentuan berikut. Jika Anda tidak menyetujui salah satu ketentuan ini, mohon untuk tidak menggunakan layanan kami.
                </p>
                <p style="line-height:1.8;color:#374151;">
                    Rumah Peduli adalah platform kemanusiaan yang menghubungkan donatur dengan panti asuhan dan kampanye penggalangan dana yang terverifikasi di Indonesia. Kami berkomitmen untuk menjaga transparansi dan akuntabilitas dalam setiap transaksi.
                </p>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">2. Akun Pengguna</h2>
                <h3 style="font-size:18px;font-weight:600;color:var(--teal);margin:20px 0 12px;">2.1 Pendaftaran</h3>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Anda harus berusia minimal 17 tahun untuk membuat akun.</li>
                    <li style="margin-bottom:8px;">Informasi yang Anda berikan harus akurat, lengkap, dan terkini.</li>
                    <li style="margin-bottom:8px;">Anda bertanggung jawab menjaga kerahasiaan kata sandi akun Anda.</li>
                    <li style="margin-bottom:8px;">Satu orang atau entitas hanya boleh memiliki satu akun aktif.</li>
                </ul>

                <h3 style="font-size:18px;font-weight:600;color:var(--teal);margin:20px 0 12px;">2.2 Jenis Akun</h3>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;"><strong>Donatur:</strong> Individu yang ingin memberikan donasi kepada panti atau kampanye.</li>
                    <li style="margin-bottom:8px;"><strong>Pengelola Panti:</strong> Organisasi panti asuhan yang telah terverifikasi oleh admin platform.</li>
                    <li style="margin-bottom:8px;"><strong>Admin:</strong> Pengelola platform yang bertanggung jawab atas verifikasi dan moderasi.</li>
                </ul>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">3. Donasi</h2>
                <h3 style="font-size:18px;font-weight:600;color:var(--teal);margin:20px 0 12px;">3.1 Proses Donasi</h3>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Semua donasi bersifat sukarela dan tidak dapat dibatalkan setelah pembayaran berhasil.</li>
                    <li style="margin-bottom:8px;">Donatur wajib mengunggah bukti transfer untuk donasi melalui transfer bank.</li>
                    <li style="margin-bottom:8px;">Setiap donasi akan diverifikasi oleh admin sebelum dicatat sebagai donasi resmi.</li>
                    <li style="margin-bottom:8px;">Proses verifikasi memakan waktu maksimal 2x24 jam kerja.</li>
                </ul>

                <h3 style="font-size:18px;font-weight:600;color:var(--teal);margin:20px 0 12px;">3.2 Metode Pembayaran</h3>
                <p style="line-height:1.8;color:#374151;">
                    Platform menerima donasi melalui transfer bank dan QRIS. Semua biaya administrasi ditanggung oleh donatur sesuai kebijakan bank/payment gateway yang digunakan.
                </p>

                <h3 style="font-size:18px;font-weight:600;color:var(--teal);margin:20px 0 12px;">3.3 Pengembalian Dana</h3>
                <p style="line-height:1.8;color:#374151;">
                    Donasi yang sudah diverifikasi tidak dapat dikembalikan kecuali dalam kondisi berikut:
                </p>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Kampanye dibatalkan atau panti dihapus dari platform sebelum dana disalurkan.</li>
                    <li style="margin-bottom:8px;">Terjadi kesalahan teknis yang mengakibatkan duplikasi transaksi.</li>
                    <li style="margin-bottom:8px;">Ditemukan indikasi penipuan atau pelanggaran oleh pihak penerima donasi.</li>
                </ul>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">4. Kewajiban Pengelola Panti</h2>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Menyediakan dokumen legalitas yang valid saat pendaftaran.</li>
                    <li style="margin-bottom:8px;">Memberikan informasi yang akurat tentang profil panti dan jumlah anak binaan.</li>
                    <li style="margin-bottom:8px;">Melaporkan penggunaan dana dengan mengunggah bukti pengeluaran yang sah.</li>
                    <li style="margin-bottom:8px;">Menjaga transparansi terhadap donatur dan admin platform.</li>
                    <li style="margin-bottom:8px;">Tidak menyalahgunakan dana donasi untuk kepentingan pribadi.</li>
                    <li style="margin-bottom:8px;">Memberikan update berkala mengenai kondisi panti dan anak-anak binaan.</li>
                </ul>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">5. Verifikasi dan Moderasi</h2>
                <p style="line-height:1.8;color:#374151;margin-bottom:12px;">
                    Admin platform berhak untuk:
                </p>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Memverifikasi atau menolak pendaftaran panti baru.</li>
                    <li style="margin-bottom:8px;">Memverifikasi atau menolak transaksi donasi yang mencurigakan.</li>
                    <li style="margin-bottom:8px;">Memverifikasi bukti pengeluaran yang dilaporkan oleh panti.</li>
                    <li style="margin-bottom:8px;">Menonaktifkan atau menghapus akun yang melanggar ketentuan.</li>
                    <li style="margin-bottom:8px;">Menghentikan kampanye yang tidak sesuai dengan misi platform.</li>
                </ul>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">6. Privasi dan Keamanan Data</h2>
                <p style="line-height:1.8;color:#374151;margin-bottom:12px;">
                    Kami menjaga kerahasiaan data pribadi Anda sesuai dengan kebijakan privasi kami. Data yang dikumpulkan meliputi:
                </p>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Informasi akun (nama, email, peran)</li>
                    <li style="margin-bottom:8px;">Riwayat transaksi donasi</li>
                    <li style="margin-bottom:8px;">Bukti pembayaran dan dokumen legalitas (untuk panti)</li>
                </ul>
                <p style="line-height:1.8;color:#374151;margin-top:12px;">
                    Data ini tidak akan dibagikan kepada pihak ketiga tanpa izin Anda, kecuali diwajibkan oleh hukum yang berlaku.
                </p>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">7. Larangan Penggunaan</h2>
                <p style="line-height:1.8;color:#374151;margin-bottom:12px;">
                    Pengguna dilarang untuk:
                </p>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Memberikan informasi palsu atau menyesatkan.</li>
                    <li style="margin-bottom:8px;">Menggunakan platform untuk penipuan atau pencucian uang.</li>
                    <li style="margin-bottom:8px;">Mengunggah konten yang melanggar hukum, mengandung ujaran kebencian, atau pornografi.</li>
                    <li style="margin-bottom:8px;">Mencoba mengakses akun orang lain tanpa izin.</li>
                    <li style="margin-bottom:8px;">Melakukan spamming atau aktivitas yang mengganggu sistem platform.</li>
                    <li style="margin-bottom:8px;">Menggunakan bot atau script otomatis untuk memanipulasi sistem.</li>
                </ul>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">8. Batasan Tanggung Jawab</h2>
                <p style="line-height:1.8;color:#374151;margin-bottom:12px;">
                    Rumah Peduli bertindak sebagai perantara antara donatur dan panti. Kami tidak bertanggung jawab atas:
                </p>
                <ul style="margin-left:24px;line-height:1.8;color:#374151;">
                    <li style="margin-bottom:8px;">Keakuratan informasi yang diberikan oleh panti setelah proses verifikasi.</li>
                    <li style="margin-bottom:8px;">Penggunaan dana oleh panti di luar yang dilaporkan kepada platform.</li>
                    <li style="margin-bottom:8px;">Kerugian finansial yang timbul dari kesalahan teknis di luar kendali kami.</li>
                    <li style="margin-bottom:8px;">Gangguan layanan akibat force majeure (bencana alam, perang, dll).</li>
                </ul>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">9. Perubahan Syarat dan Ketentuan</h2>
                <p style="line-height:1.8;color:#374151;">
                    Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diumumkan melalui platform dan berlaku efektif sejak tanggal yang ditentukan. Penggunaan platform setelah perubahan berarti Anda menyetujui ketentuan yang baru.
                </p>
            </section>

            <section style="margin-bottom:40px;">
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">10. Hukum yang Berlaku</h2>
                <p style="line-height:1.8;color:#374151;">
                    Syarat dan ketentuan ini diatur oleh hukum Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan melalui musyawarah atau melalui pengadilan yang berwenang di Indonesia.
                </p>
            </section>

            <section>
                <h2 style="font-size:24px;font-weight:700;color:var(--teal-dark);margin-bottom:16px;">11. Kontak</h2>
                <p style="line-height:1.8;color:#374151;margin-bottom:12px;">
                    Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi kami:
                </p>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin-top:16px;">
                    <p style="margin-bottom:8px;"><strong>Email:</strong> <a href="mailto:halo@rumahpeduli.id" style="color:var(--green);text-decoration:underline;">halo@rumahpeduli.id</a></p>
                    <p style="margin-bottom:8px;"><strong>Telepon:</strong> +62 21 5084 2026</p>
                    <p style="margin:0;"><strong>Alamat:</strong> Jl. Kepedulian No. 18, Jakarta Selatan</p>
                </div>
            </section>

            <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--line);text-align:center;">
                <p style="font-size:14px;color:#6b7280;">
                    Terakhir diperbarui: 6 November 2026
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
