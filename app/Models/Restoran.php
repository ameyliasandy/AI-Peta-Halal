<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
 
class Restoran extends Model
{
    protected $table = 'restoran';
    protected $primaryKey = 'id_restoran';
    protected $fillable = [
        'nama_restoran',
        'id_pemilik',
        'id_kategori',
        'id_sub_kategori',
        'alamat',
        'kecamatan_kelurahan',
        'kota',
        'provinsi',
        'kode_pos',
        'jam_operasional',
        'deskripsi',
        'status_halal',
        'foto_utama',
        'harga_rata_rata_min',
        'harga_rata_rata_max',
        'kapasitas_tempat',
        'latitude',
        'longitude',
        'no_telepon',
        'email_usaha',
        'website_sosmed'
    ];
 
    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'rating'    => 'float',
        'status_buka' => 'boolean',
    ];
 
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'id_pemilik', 'id');
    }
 
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
 
    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class, 'id_sub_kategori', 'id_sub_kategori');
    }
 
    public function menu()
    {
        return $this->hasMany(Menu::class, 'id_restoran', 'id_restoran');
    }
 
    public function verifikasiHalal()
    {
        return $this->hasOne(VerifikasiHalal::class, 'id_restoran', 'id_restoran')
                    ->latestOfMany('id_verifikasi');
    }
 
    public function semuaVerifikasi()
    {
        return $this->hasMany(VerifikasiHalal::class, 'id_restoran', 'id_restoran');
    }
 
    public function getStatusHalalLabelAttribute(): string
    {
        return match($this->status_halal) {
            'certified'    => 'Certified Halal',
            'self_claimed' => 'Self-Claimed Halal',
            default        => 'Belum Terverifikasi',
        };
    }
 
    public function getHargaRataRataAttribute(): string
    {
        if ($this->harga_rata_rata_min && $this->harga_rata_rata_max) {
            return 'Rp ' . number_format($this->harga_rata_rata_min, 0, ',', '.') .
                   ' - ' . number_format($this->harga_rata_rata_max, 0, ',', '.');
        }
        return '-';
    }
}