<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RekomendasiTest extends TestCase
{
    public function test_halaman_rekomendasi()
    {
        $user = User::where('email', 'saskia@gmail.com')->first();

        $this->actingAs($user);

        $response = $this->get('/rekomendasi');

        $response->assertStatus(200);
    }

    /** @test */
    public function detail_restoran_tidak_ditemukan()
    {
        $response = $this->get('/restoran/999999');

        $response->assertStatus(404);
    }
}