<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panti extends Model
{
    use SoftDeletes;

    protected $table = 'panti';

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'kota',
        'deskripsi',
        'jumlah_anak',
        'foto',
        'dokumen',
        'status_verifikasi',
        'catatan_admin',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'jumlah_anak' => 'integer',
        'verified_at' => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Nama "kampanye" dipakai konsisten di seluruh project.
     */
    public function kampanye(): HasMany
    {
        return $this->hasMany(Kampanye::class, 'panti_id');
    }

    /**
     * Alias agar kode lama yang memakai "campaigns" tetap bekerja.
     */
    public function campaigns(): HasMany
    {
        return $this->kampanye();
    }

    public function needs(): HasMany
    {
        return $this->hasMany(Kebutuhan::class, 'panti_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'panti_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'panti_id');
    }
}
