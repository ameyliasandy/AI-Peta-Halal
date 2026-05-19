<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
 
class VerifikasiHalal extends Model
{
    protected $table = 'verifikasi_halal';
    protected $primaryKey = 'id_verifikasi';
    public $timestamps = false;
    protected $fillable = [
        'id_admin', 'id_restoran', 'no_sertifikat', 'lembaga_penerbit',
        'masa_berlaku', 'dokumen_sertifikat', 'tanggal_verifikasi',
        'status', 'catatan',
        'bebas_babi', 'daging_halal', 'bumbu_bebas_alkohol', 'kemasan_halal',
        'peralatan_tidak_najis', 'tidak_jual_alkohol',
        'dapur_bersih', 'karyawan_bersih', 'sop_kebersihan'
    ];
 
    protected $casts = [
        'masa_berlaku'       => 'date',
        'tanggal_verifikasi' => 'date',
        'bebas_babi'         => 'boolean',
        'daging_halal'       => 'boolean',
        'bumbu_bebas_alkohol'=> 'boolean',
        'kemasan_halal'      => 'boolean',
        'peralatan_tidak_najis' => 'boolean',
        'tidak_jual_alkohol' => 'boolean',
        'dapur_bersih'       => 'boolean',
        'karyawan_bersih'    => 'boolean',
        'sop_kebersihan'     => 'boolean',
    ];
 

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
 
    public function restoran()
    {
        return $this->belongsTo(Restoran::class, 'id_restoran', 'id_restoran');
    }
 
    public function isSertifikatHampirExpire(): bool
    {
        if (!$this->masa_berlaku) return false;
        return $this->masa_berlaku->diffInDays(now()) <= 30 && $this->masa_berlaku->isFuture();
    }
}