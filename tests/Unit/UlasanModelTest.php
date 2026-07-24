<?php

namespace Tests\Unit;

use App\Models\Ulasan;
use Tests\TestCase;

class UlasanModelTest extends TestCase
{
    public function test_relasi_user_ada()
    {
        $ulasan = new Ulasan();

        $this->assertTrue(method_exists($ulasan, 'user'));
    }

    public function test_relasi_restoran_ada()
    {
        $ulasan = new Ulasan();

        $this->assertTrue(method_exists($ulasan, 'restoran'));
    }
}