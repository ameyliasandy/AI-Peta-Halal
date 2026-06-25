<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';

    protected $fillable = [
        'user_id', 'id_restoran', 'rating', 'komentar',
    ];

    public function restoran()
    {
        return $this->belongsTo(Restoran::class, 'id_restoran', 'id_restoran');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}