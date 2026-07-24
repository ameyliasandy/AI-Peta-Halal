<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_user_memiliki_role()
    {
        $user = User::first();

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->role);
    }

    public function test_user_memiliki_relasi_favorit()
    {
        $user = User::first();

        $this->assertTrue(
            method_exists($user, 'favorit')
        );
    }

    public function test_user_memiliki_relasi_rekomendasi()
    {
        $user = User::first();

        $this->assertTrue(
            method_exists($user, 'rekomendasi')
        );
    }
}