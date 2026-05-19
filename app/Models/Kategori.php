<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;
    protected $fillable = ['nama_kategori', 'slug'];
 
    public function subKategori()
    {
        return $this->hasMany(SubKategori::class, 'id_kategori', 'id_kategori');
    }
 
    public function restoran()
    {
        return $this->hasMany(Restoran::class, 'id_kategori', 'id_kategori');
    }
}