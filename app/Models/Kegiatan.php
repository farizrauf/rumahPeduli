<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'panti_id',
        'judul',
        'deskripsi',
        'tanggal',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class, 'panti_id');
    }
}
