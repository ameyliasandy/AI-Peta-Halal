<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Rekomendasi;
 
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

public function ulasan()
{
    return $this->hasMany(Ulasan::class, 'id_restoran', 'id_restoran');
}

public function rekomendasi()
{
    return $this->hasMany(Rekomendasi::class, 'id_restoran', 'id_restoran');
}

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
    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_restoran', 'id_restoran');
    }

 public function getFotoUtamaAttribute($value)
{
    if (!empty($value)) {
        return asset('storage/' . $value);
    }

    $text = strtolower(
        ($this->nama_restoran ?? '') . ' ' .
        ($this->deskripsi ?? '')
    );

    // Seafood
    if (
        str_contains($text, 'seafood') ||
        str_contains($text, 'ikan')
    ) {
        return asset('images/restoran/seafood.jpg');
    }

    // Ayam & Bebek
    if (
        str_contains($text, 'ayam') ||
        str_contains($text, 'bebek') ||
        str_contains($text, 'fried chicken')
    ) {
        return asset('images/restoran/ayam.jpg');
    }

    // Mie & Jepang
    if (
        str_contains($text, 'mie') ||
        str_contains($text, 'noodle') ||
        str_contains($text, 'ramen') ||
        str_contains($text, 'udon') ||
        str_contains($text, 'jepang')
    ) {
        return asset('images/restoran/mie.jpg');
    }

    // Bakery & Dessert
    if (
        str_contains($text, 'bakery') ||
        str_contains($text, 'roti') ||
        str_contains($text, 'cake') ||
        str_contains($text, 'dessert') ||
        str_contains($text, 'patisserie') ||
        str_contains($text, 'kue')
    ) {
        return asset('images/restoran/bakery.jpg');
        // nanti kalau punya bakery.jpg ganti ke bakery.jpg
    }

    // Nusantara
    if (
        str_contains($text, 'nusantara') ||
        str_contains($text, 'sunda') ||
        str_contains($text, 'padang') ||
        str_contains($text, 'kebuli') ||
        str_contains($text, 'martabak') ||
        str_contains($text, 'madura') ||
        str_contains($text, 'jawa')
    ) {
        return asset('images/restoran/nusantara.jpg');
    }

    // Burger & Fast Food
    if (
        str_contains($text, 'burger') ||
        str_contains($text, 'kfc')
    ) {
        return asset('images/restoran/ayam.jpg');
    }

    // Daging / Grill
    if (
        str_contains($text, 'grill') ||
        str_contains($text, 'daging') ||
        str_contains($text, 'sapi') ||
        str_contains($text, 'meat')
    ) {
        return asset('images/restoran/nusantara.jpg');
    }

    return asset('images/restoran/default.jpg');
}
}