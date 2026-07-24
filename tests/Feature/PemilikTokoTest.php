<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Restoran;
use Tests\TestCase;

class PemilikTokoTest extends TestCase
{
    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::where('role', 'pemilik_usaha')->first();

        if (!$this->owner) {
            $this->markTestSkipped('Owner tidak ditemukan.');
        }

        $this->actingAs($this->owner);
    }

    /** @test */
    public function owner_bisa_membuka_halaman_toko()
    {
        $this->get('/pemilik/toko')
             ->assertStatus(200);
    }

    /** @test */
    public function owner_bisa_membuka_form_create()
    {
        $this->get('/pemilik/toko/create')
             ->assertStatus(200);
    }

    /** @test */
    public function owner_bisa_melihat_detail_tokonya()
    {
        $restoran = Restoran::where(
            'id_pemilik',
            $this->owner->id
        )->first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $this->get("/pemilik/toko/{$restoran->id_restoran}")
             ->assertStatus(200);
    }

    /** @test */
    public function owner_bisa_membuka_form_edit()
    {
        $restoran = Restoran::where(
            'id_pemilik',
            $this->owner->id
        )->first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $this->get("/pemilik/toko/{$restoran->id_restoran}/edit")
             ->assertStatus(200);
    }
}