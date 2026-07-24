<?php

namespace Tests\Unit;

use App\Models\Restoran;
use Tests\TestCase;

class RestoranModelTest extends TestCase
{
    public function test_relasi_kategori_ada()
    {
        $restoran = new Restoran();

        $this->assertTrue(method_exists($restoran, 'kategori'));
    }

    public function test_relasi_menu_ada()
    {
        $restoran = new Restoran();

        $this->assertTrue(method_exists($restoran, 'menu'));
    }

    public function test_relasi_verifikasi_halal_ada()
    {
        $restoran = new Restoran();

        $this->assertTrue(method_exists($restoran, 'verifikasiHalal'));
    }

    public function test_relasi_ulasan_ada()
    {
        $restoran = new Restoran();

        $this->assertTrue(method_exists($restoran, 'ulasan'));
    }
}