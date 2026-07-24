<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restoran;

class FavoritTest extends TestCase
{
    private function loginPencari()
    {
        $user = User::where('email', 'saskia@gmail.com')->first();

        $this->assertNotNull($user);

        $this->actingAs($user);

        return $user;
    }

    public function test_halaman_favorit_bisa_diakses()
    {
        $this->loginPencari();

        $response = $this->get('/favorit');

        $response->assertStatus(200);
    }

    public function test_toggle_favorit()
    {
        $user = $this->loginPencari();

        $restoran = Restoran::first();

        if (!$restoran) {
            $this->markTestSkipped('Belum ada restoran.');
        }

        $response = $this->post('/favorit/toggle', [
            'id_restoran' => $restoran->id_restoran,
        ]);

        $response->assertStatus(200);
    }

    public function test_cek_status_favorit()
    {
        $this->loginPencari();

        $restoran = Restoran::first();

        if (!$restoran) {
            $this->markTestSkipped('Belum ada restoran.');
        }

        $response = $this->get("/favorit/cek/{$restoran->id_restoran}");

        $response->assertStatus(200);
    }
}