<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $table = 'favorit';

    protected $fillable = [
        'user_id',
        'id_restoran',
        'id_menu'
    ];


    public function restoran()
    {
        return $this->belongsTo(
            Restoran::class,
            'id_restoran',
            'id_restoran'
        );
    }


    public function menu()
    {
        return $this->belongsTo(
            Menu::class,
            'id_menu',
            'id_menu'
        );
    }
}