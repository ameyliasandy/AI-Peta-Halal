<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restoran;

class UlasanTest extends TestCase
{
    public function test_guest_tidak_bisa_memberi_ulasan()
    {
        $restoran = Restoran::first();

        $response = $this->post('/ulasan', [
            'id_restoran' => $restoran->id_restoran,
            'rating' => 5,
            'komentar' => 'Enak',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_pencari_bisa_memberi_ulasan()
    {
        $user = User::where('role', 'pencari')->first();
        $restoran = Restoran::first();

        $response = $this
            ->actingAs($user)
            ->post('/ulasan', [
                'id_restoran' => $restoran->id_restoran,
                'rating' => 5,
                'komentar' => 'Mantap',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('ulasan', [
            'user_id' => $user->id,
            'id_restoran' => $restoran->id_restoran,
            'rating' => 5,
        ]);
    }

    public function test_rating_wajib_diisi()
    {
        $user = User::where('role', 'pencari')->first();
        $restoran = Restoran::first();

        $response = $this
            ->actingAs($user)
            ->post('/ulasan', [
                'id_restoran' => $restoran->id_restoran,
                'komentar' => 'Mantap',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_ulasan_bisa_diupdate()
    {
        $user = User::where('role', 'pencari')->first();
        $restoran = Restoran::first();

        $this->actingAs($user)->post('/ulasan', [
            'id_restoran' => $restoran->id_restoran,
            'rating' => 3,
            'komentar' => 'Lumayan',
        ]);

        $this->actingAs($user)->post('/ulasan', [
            'id_restoran' => $restoran->id_restoran,
            'rating' => 5,
            'komentar' => 'Sekarang enak',
        ]);

        $this->assertDatabaseHas('ulasan', [
            'user_id' => $user->id,
            'id_restoran' => $restoran->id_restoran,
            'rating' => 5,
            'komentar' => 'Sekarang enak',
        ]);
    }
}