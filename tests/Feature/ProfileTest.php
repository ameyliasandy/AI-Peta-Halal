<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileTest extends TestCase
{
    public function test_profile_bisa_diakses()
    {
        $user = User::where('role', 'pencari')->first();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertStatus(200);
    }

    public function test_profile_bisa_diupdate()
    {
        $user = User::where('role', 'pencari')->first();

        $response = $this
            ->actingAs($user)
            ->post('/profile', [
                'name' => 'Nama PHPUnit',
                'email' => $user->email,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama PHPUnit',
        ]);
    }

    public function test_password_bisa_diubah()
    {
        $user = User::factory()->create([
            'role' => 'pencari',
            'password' => bcrypt('password'),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/profile/password', [
                'password_lama' => 'password',
                'password_baru' => 'passwordbaru123',
                'password_baru_confirmation' => 'passwordbaru123',
            ]);

        $response->assertRedirect();

        $user->refresh();

        $this->assertTrue(
            Hash::check('passwordbaru123', $user->password)
        );
    }

    public function test_akun_bisa_dihapus()
    {
        $user = User::factory()->create([
            'role' => 'pencari',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/profile/hapus');

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}