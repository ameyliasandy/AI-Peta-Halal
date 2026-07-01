<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pencarian extends Model
{
    protected $table = 'pencarian';
    protected $primaryKey = 'id_pencarian';
    public $timestamps = false; 

    protected $fillable = [
        'id_pencari', 'keyword', 'lokasi', 'waktu',
    ];
}