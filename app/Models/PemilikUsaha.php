<?php

namespace App\Models;

class PemilikUsaha extends User
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected static function booted()
    {
        static::addGlobalScope('pemilik', function ($query) {
            $query->where('role', 'pemilik_usaha');
        });
    }

    public function restoran()
    {
        return $this->hasMany(Restoran::class, 'id_pemilik', 'id');
    }
}