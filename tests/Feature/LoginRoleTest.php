<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoginRoleTest extends TestCase
{
    public function test_admin_redirect()
    {
        $user = User::where('email', 'admin@halalfood.com')->first();

        $this->assertNotNull($user);

        $response = $this->post('/login', [
            'email' => 'admin@halalfood.com',
            'password' => 'admin123'
        ]);

        $response->assertRedirect('/admin/index');
    }

    public function test_owner_redirect()
    {
        $user = User::where('email', 'suardi@gmail.com')->first();

        $this->assertNotNull($user);

        $response = $this->post('/login', [
            'email' => 'suardi@gmail.com',
            'password' => '123456'
        ]);

        $response->assertRedirect('/pemilik/dashboard');
    }

    public function test_pencari_after_onboarding_redirect()
    {
        $user = User::where('email', 'saskia@gmail.com')->first();

        $this->assertNotNull($user);

        $this->assertTrue(
            DB::table('preferensi_users')
                ->where('user_id', $user->id)
                ->exists()
        );

        $response = $this->post('/login', [
            'email' => 'saskia@gmail.com',
            'password' => '123456'
        ]);

        $response->assertRedirect('/dashboard');
    }
}