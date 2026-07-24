<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class SearchTest extends TestCase
{
    private function loginPencari()
    {
        $user = User::where('email', 'saskia@gmail.com')->first();

        $this->assertNotNull($user);

        $this->actingAs($user);
    }

    public function test_halaman_dashboard_bisa_diakses()
    {
        $this->loginPencari();

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_search_restoran_dengan_keyword()
    {
        $this->loginPencari();

        $response = $this->get('/dashboard?search=ayam');

        $response->assertStatus(200);
    }

    public function test_search_restoran_tanpa_keyword()
    {
        $this->loginPencari();

        $response = $this->get('/dashboard?search=');

        $response->assertStatus(200);
    }

    public function test_filter_murah()
    {
        $this->loginPencari();

        $response = $this->get('/dashboard?filter=murah');

        $response->assertStatus(200);
    }

    public function test_filter_pedas()
    {
        $this->loginPencari();

        $response = $this->get('/dashboard?filter=pedas');

        $response->assertStatus(200);
    }

    public function test_search_tidak_ditemukan()
    {
        $this->loginPencari();

        $response = $this->get('/dashboard?search=xyzabc999');

        $response->assertStatus(200);

        $response->assertSee('Tidak ada');
    }
}