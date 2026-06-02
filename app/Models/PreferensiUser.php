<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferensiUser extends Model
{
    protected $table = 'preferensi_users';

    protected $fillable = ['user_id', 'kategori'];
}