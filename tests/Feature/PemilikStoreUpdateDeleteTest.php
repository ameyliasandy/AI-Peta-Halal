<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restoran;
use App\Models\Kategori;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PemilikStoreUpdateDeleteTest extends TestCase
{
    public function test_owner_bisa_menambah_usaha()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();

        $kategori = Kategori::first();

        $this->assertNotNull($owner);

        $response = $this
            ->actingAs($owner)
            ->post('/pemilik/toko', [
                'nama_restoran' => 'PHPUnit Resto',
                'id_kategori' => $kategori->id_kategori,
                'deskripsi' => 'Restoran untuk testing',
                'alamat' => 'Batam Centre',
                'kecamatan_kelurahan' => 'Batam Centre',
                'kota' => 'Batam',
                'provinsi' => 'Kepulauan Riau',
                'latitude' => 1.123456,
                'longitude' => 104.123456,
                'jam_operasional' => '08:00-21:00',
                'no_telepon' => '08123456789',
                'punya_sertifikat' => 'tidak',
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('restoran', [
            'nama_restoran' => 'PHPUnit Resto'
        ]);
    }

    public function test_owner_tidak_bisa_menambah_tanpa_nama()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();

        $response = $this
            ->actingAs($owner)
            ->post('/pemilik/toko', [
                'nama_restoran' => '',
                'alamat' => 'Batam',
                'kota' => 'Batam',
                'provinsi' => 'Kepri',
            ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_owner_bisa_update_usaha()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();

        $restoran = Restoran::where('id_pemilik', $owner->id)->first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $response = $this
            ->actingAs($owner)
            ->put("/pemilik/toko/{$restoran->id_restoran}", [
                'nama_restoran' => 'Nama Baru PHPUnit',
                'deskripsi' => $restoran->deskripsi,
                'alamat' => $restoran->alamat,
                'kecamatan_kelurahan' => $restoran->kecamatan_kelurahan,
                'kota' => $restoran->kota,
                'provinsi' => $restoran->provinsi,
                'kode_pos' => $restoran->kode_pos,
                'id_kategori' => $restoran->id_kategori,
                'jam_operasional' => $restoran->jam_operasional,
                'no_telepon' => $restoran->no_telepon,
                'email_usaha' => $restoran->email_usaha,
                'website_sosmed' => $restoran->website_sosmed,
                'harga_rata_rata_min' => $restoran->harga_rata_rata_min,
                'harga_rata_rata_max' => $restoran->harga_rata_rata_max,
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('restoran', [
            'id_restoran' => $restoran->id_restoran,
            'nama_restoran' => 'Nama Baru PHPUnit',
        ]);
    }

    public function test_owner_bisa_hapus_usaha()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();
        $kategori = Kategori::first();
        $restoran = Restoran::create([
            'id_pemilik' => $owner->id,
            'id_kategori' => $kategori->id_kategori,

            'nama_restoran' => 'Resto Hapus PHPUnit',
            'deskripsi' => 'Testing restoran',

            'alamat' => 'Batam',
            'kecamatan_kelurahan' => 'Batam Centre',
            'kota' => 'Batam',
            'provinsi' => 'Kepri',

            'latitude' => 1.123456,
            'longitude' => 104.123456,

            'jam_operasional' => '08:00-20:00',

            'no_telepon' => '08123456789',

            'status_halal' => 'self_claimed',
        ]);

        $response = $this
            ->actingAs($owner)
            ->delete("/pemilik/toko/{$restoran->id_restoran}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('restoran', [
            'id_restoran' => $restoran->id_restoran
        ]);
    }

    /** @test */
    public function upload_sertifikat_berhasil()
    {
        Storage::fake('public');

        $owner = User::where('role', 'pemilik_usaha')->first();
        $kategori = Kategori::first();

        $response = $this->actingAs($owner)
            ->post('/pemilik/toko', [
                'nama_restoran' => 'Tes',
                'id_kategori' => $kategori->id_kategori,

                'deskripsi' => 'Restoran Testing',

                'alamat' => 'Batam',
                'kota' => 'Batam',
                'provinsi' => 'Kepri',

                'latitude' => 1.123456,
                'longitude' => 104.123456,

                'jam_operasional' => '08:00-20:00',

                'no_telepon' => '08123456789',

                'dokumen_sertifikat' => UploadedFile::fake()->create(
                    'sertifikat.pdf',
                    100,
                    'application/pdf'
                ),
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function upload_sertifikat_gagal_format_salah()
    {
        Storage::fake('public');
        $owner = User::where('role', 'pemilik_usaha')->first();
        $kategori = Kategori::first();

        $response = $this->actingAs($owner)
            ->post('/pemilik/toko', [
                'nama_restoran' => 'Tes',
                'id_kategori' => $kategori->id_kategori,

                'deskripsi' => 'Restoran Testing',

                'alamat' => 'Batam',
                'kota' => 'Batam',
                'provinsi' => 'Kepri',

                'latitude' => 1.123456,
                'longitude' => 104.123456,

                'jam_operasional' => '08:00-20:00',

                'no_telepon' => '08123456789',

                'dokumen_sertifikat' => UploadedFile::fake()->create(
                    'sertifikat.txt',
                    10,
                    'text/plain'

                ),
            ]);
        $response->dump();
        $response->assertStatus(422);
    }
}