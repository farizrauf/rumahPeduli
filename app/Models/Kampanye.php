<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampanye extends Model
{
    protected $table = 'kampanye';

    protected $fillable = [
        'panti_id',
        'judul',
        'deskripsi',
        'kategori',
        'target_dana',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'target_dana'     => 'decimal:2',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    // ─── Relasi ──────────────────────────────────────────────────

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class, 'panti_id');
    }

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class, 'kampanye_id');
    }

    public function verifiedDonations(): HasMany
    {
        return $this->donasi()->where('status', 'verified');
    }

    // ─── Accessor ────────────────────────────────────────────────

    /**
     * Total dana yang sudah diverifikasi.
     * Dipakai di campaign-show.blade.php: $kampanye->dana_terkumpul
     */
    public function getDanaTerkumpulAttribute(): float
    {
        // Jika sudah di-load lewat withSum, pakai nilai itu langsung.
        if (isset($this->attributes['dana_terkumpul'])) {
            return (float) $this->attributes['dana_terkumpul'];
        }

        return (float) $this->verifiedDonations()->sum('nominal');
    }

    /**
     * Jumlah donatur unik yang sudah terverifikasi.
     * Dipakai di campaign-show.blade.php: $kampanye->jumlah_donatur
     */
    public function getJumlahDonaturAttribute(): int
    {
        return $this->verifiedDonations()->distinct('user_id')->count('user_id');
    }
}
