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
        'target_dana' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

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
        // Relasi ini dipakai saat menghitung dana yang benar-benar masuk.
        return $this->donasi()->where('status', 'verified');
    }
}
