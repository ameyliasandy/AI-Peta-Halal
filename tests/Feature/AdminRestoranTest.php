<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Restoran;
use App\Models\VerifikasiHalal;
use Tests\TestCase;

class AdminRestoranTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'admin')->first();

        if (!$this->admin) {
            $this->markTestSkipped('Admin tidak ditemukan.');
        }

        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_bisa_membuka_halaman_restoran()
    {
        $response = $this->get('/admin/restoran');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_bisa_export_csv()
    {
        $response = $this->get('/admin/restoran/export-csv');

        $response->assertStatus(200);

        $response->assertHeader(
            'content-type',
            'text/csv; charset=UTF-8'
        );
    }

    /** @test */
    public function admin_bisa_melihat_detail_restoran()
    {
        $restoran = Restoran::first();

        if (!$restoran) {
            $this->markTestSkipped('Tidak ada data restoran.');
        }

        $response = $this->get("/admin/restoran/{$restoran->id_restoran}");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_bisa_mengambil_data_edit()
    {
        $restoran = Restoran::first();

        if (!$restoran) {
            $this->markTestSkipped('Tidak ada data restoran.');
        }

        $response = $this->get("/admin/restoran/{$restoran->id_restoran}/data");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'restoran',
                     'verifikasi'
                 ]);
    }

    /** @test */
    public function update_status_verifikasi_berhasil()
    {
        $verifikasi = VerifikasiHalal::first();

        if (!$verifikasi) {
            $this->markTestSkipped('Tidak ada data verifikasi.');
        }

        $response = $this->post(
            "/admin/restoran/{$verifikasi->id_restoran}/verifikasi",
            [
                'status_verifikasi' => 'terverifikasi',
                'catatan' => 'Lulus PHPUnit'
            ]
        );

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /** @test */
    /** @test */
public function admin_menolak_pengajuan()
{
    $verifikasi = VerifikasiHalal::first();

    if (!$verifikasi) {
        $this->markTestSkipped('Tidak ada data verifikasi.');
    }

    $response = $this->post(
        "/admin/restoran/{$verifikasi->id_restoran}/verifikasi",
        [
            'status_verifikasi' => 'ditolak',
            'catatan' => 'Dokumen belum lengkap'
        ]
    );

    $response->assertStatus(200)
             ->assertJson([
                 'success' => true
             ]);
}
}