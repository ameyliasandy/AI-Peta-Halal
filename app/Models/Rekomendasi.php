<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasi';
    
    protected $fillable = [
        'user_id',
        'id_restoran',
        'score',
        'rank'
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke Restoran
    public function restoran(): BelongsTo
    {
        return $this->belongsTo(Restoran::class, 'id_restoran', 'id_restoran');
    }
}