<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kebutuhan extends Model
{
    protected $table = 'kebutuhan';

    protected $fillable = [
        'panti_id',
        'nama_barang',
        'kategori',
        'jumlah',
        'prioritas',
        'status',
    ];

    public function panti(): BelongsTo
    {
        return $this->belongsTo(Panti::class, 'panti_id');
    }
}
