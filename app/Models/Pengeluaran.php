<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'panti_id',
        'kampanye_id',
        'keterangan',
        'nominal',
        'tanggal',
        'bukti',
        'status',
        'catatan_admin',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'nominal'     => 'decimal:2',
        'tanggal'     => 'date',
        'verified_at' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────────

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class, 'panti_id');
    }

    public function kampanye(): BelongsTo
    {
        return $this->belongsTo(Kampanye::class, 'kampanye_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
