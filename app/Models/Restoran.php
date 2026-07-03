<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Rekomendasi;
use Carbon\Carbon;

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
        'website_sosmed',
        'status_buka'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'rating' => 'float',
        'status_buka' => 'boolean',
    ];

    // ============= RELASI =============
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

    public function menus()
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

    // ============= METODE RATING & ULASAN =============
    /**
     * Hitung rata-rata rating dari semua ulasan
     */
    public function rataRataRating()
    {
        return $this->ulasan()->avg('rating') ?? 0;
    }

    /**
     * Hitung jumlah ulasan
     */
    public function jumlahUlasan()
    {
        return $this->ulasan()->count();
    }

    /**
     * Accessor untuk rating rata-rata
     */
    public function getRatingRataRataAttribute()
    {
        return $this->ulasan()->avg('rating') ?? 0;
    }

    /**
     * Accessor untuk total ulasan
     */
    public function getTotalUlasanAttribute()
    {
        return $this->ulasan()->count();
    }

    // ============= LABEL & FORMAT =============
    public function getStatusHalalLabelAttribute(): string
    {
        return match($this->status_halal) {
            'certified' => 'Certified Halal',
            'self_claimed' => 'Self-Claimed Halal',
            default => 'Belum Terverifikasi',
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

    // ============= FOTO =============
    public function getFotoUtamaUrl()
    {
        if (!empty($this->foto_utama)) {
            return asset('storage/' . $this->foto_utama);
        }

        return $this->getDefaultFoto();
    }

    private function getDefaultFoto()
    {
        $text = strtolower(
            ($this->nama_restoran ?? '') . ' ' .
            ($this->deskripsi ?? '')
        );

        if (str_contains($text, 'seafood') || str_contains($text, 'ikan')) {
            return asset('images/restoran/seafood.jpg');
        }

        if (str_contains($text, 'ayam') || str_contains($text, 'bebek') || str_contains($text, 'fried chicken')) {
            return asset('images/restoran/ayam.jpg');
        }

        if (str_contains($text, 'mie') || str_contains($text, 'noodle') || 
            str_contains($text, 'ramen') || str_contains($text, 'udon') || str_contains($text, 'jepang')) {
            return asset('images/restoran/mie.jpg');
        }

        if (str_contains($text, 'bakery') || str_contains($text, 'roti') || 
            str_contains($text, 'cake') || str_contains($text, 'dessert') || 
            str_contains($text, 'patisserie') || str_contains($text, 'kue')) {
            return asset('images/restoran/bakery.jpg');
        }

        if (str_contains($text, 'nusantara') || str_contains($text, 'sunda') || 
            str_contains($text, 'padang') || str_contains($text, 'kebuli') || 
            str_contains($text, 'martabak') || str_contains($text, 'madura') || str_contains($text, 'jawa')) {
            return asset('images/restoran/nusantara.jpg');
        }

        if (str_contains($text, 'burger') || str_contains($text, 'kfc')) {
            return asset('images/restoran/ayam.jpg');
        }

        if (str_contains($text, 'grill') || str_contains($text, 'daging') || 
            str_contains($text, 'sapi') || str_contains($text, 'meat')) {
            return asset('images/restoran/nusantara.jpg');
        }

        return asset('images/restoran/default.jpg');
    }

    public function getSedangBukaAttribute()
    {
        if (!$this->jam_operasional) {
            return false;
        }

        $jam = str_replace(' ', '', $this->jam_operasional);

        if (!str_contains($jam, '-')) {
            return false;
        }

        [$buka, $tutup] = explode('-', $jam);

        $now = Carbon::now();

        $jamBuka = Carbon::today()->setTimeFromTimeString($buka);
        $jamTutup = Carbon::today()->setTimeFromTimeString($tutup);

        if ($jamTutup->lessThan($jamBuka)) {
            $jamTutup->addDay();

            if ($now->lessThan($jamBuka)) {
                $now->addDay();
            }
        }

        return $now->between($jamBuka, $jamTutup);
    }
}