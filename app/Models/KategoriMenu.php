<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class KategoriMenu extends Model
{
    protected $table = 'kategori_menu';
    protected $primaryKey = 'id_kategori_menu';
    public $timestamps = false;
    protected $fillable = ['nama_kategori'];
 
    public function menu()
    {
        return $this->hasMany(Menu::class, 'id_kategori', 'id_kategori_menu');
    }
}