<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    protected $fillable = [
        'id_restoran', 'id_kategori', 'nama_menu',
        'deskripsi', 'harga', 'foto_menu', 'tersedia'
    ];
 
    protected $casts = [
        'harga'    => 'integer',
        'tersedia' => 'boolean',
    ];
 
    public function restoran()
    {
        return $this->belongsTo(Restoran::class, 'id_restoran', 'id_restoran');
    }
 
    public function kategoriMenu()
    {
        return $this->belongsTo(KategoriMenu::class, 'id_kategori', 'id_kategori_menu');
    }
 
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}