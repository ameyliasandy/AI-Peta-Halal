<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Restoran;

class DashboardTest extends TestCase
{
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_pencari_can_access_dashboard()
    {
        $user = User::where('email', 'saskia@gmail.com')->first();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

}