<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama', 150);
            $table->text('alamat');
            $table->string('kota', 100)->index();
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('jumlah_anak')->default(0);
            $table->string('foto')->nullable();
            $table->string('dokumen')->nullable();
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending')->index();
            $table->string('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('kampanye', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_id')->constrained('panti')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->string('kategori', 50)->nullable();
            $table->decimal('target_dana', 15, 2)->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('kebutuhan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_id')->constrained('panti')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nama_barang', 150);
            $table->string('kategori', 50);
            $table->unsignedInteger('jumlah')->default(1);
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['dibutuhkan', 'terpenuhi'])->default('dibutuhkan');
            $table->timestamps();
        });

        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_id')->constrained('panti')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('kampanye_id')->constrained('kampanye')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_transaksi', 30)->unique();
            $table->decimal('nominal', 15, 2);
            $table->string('metode', 50);
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->index();
            $table->string('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panti_id')->constrained('panti')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('kampanye_id')->nullable()->constrained('kampanye')->nullOnDelete()->cascadeOnUpdate();
            $table->text('keterangan');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->index();
            $table->string('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('donasi');
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('kebutuhan');
        Schema::dropIfExists('kampanye');
        Schema::dropIfExists('panti');
    }
};
