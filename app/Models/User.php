<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rekomendasi;
use App\Models\Restoran;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'role',
        'foto_profil',    
        'no_telepon',     
        'notif_promo',    
        'notif_ulasan',   
    ];

// app/Models/User.php

// Tambahkan setelah relasi yang sudah ada

public function rekomendasi()
{
    return $this->hasMany(Rekomendasi::class, 'user_id', 'id');
}

public function rekomendasiRestoran()
{
    return $this->hasManyThrough(
        Restoran::class,
        Rekomendasi::class,
        'user_id',        // Foreign key di rekomendasi
        'id_restoran',    // Foreign key di restoran
        'id',             // Local key di users
        'id_restoran'     // Local key di rekomendasi
    );
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notif_promo'       => 'boolean',   
            'notif_ulasan'      => 'boolean',   
        ];
    }

public function favorit()
{
    return $this->hasMany(Favorit::class,'user_id');
}
}
