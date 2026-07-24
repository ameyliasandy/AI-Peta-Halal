<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restoran;
use App\Models\Menu;
use App\Models\KategoriMenu;

class MenuCrudTest extends TestCase
{
    public function test_owner_bisa_tambah_menu()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();
        $restoran = Restoran::where('id_pemilik', $owner->id)->first();
        $kategori = KategoriMenu::first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $response = $this
            ->actingAs($owner)
            ->post('/pemilik/toko/menu', [
                'nama_menu' => 'Ayam Bakar PHPUnit',
                'harga' => 25000,
                'deskripsi' => 'Menu testing',
                'id_kategori_menu' => $kategori?->id_kategori_menu,
                'tersedia' => 1,
            ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);

        $this->assertDatabaseHas('menu', [
            'nama_menu' => 'Ayam Bakar PHPUnit'
        ]);
    }

    public function test_owner_bisa_update_menu()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();
        $restoran = Restoran::where('id_pemilik', $owner->id)->first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $menu = Menu::where('id_restoran', $restoran->id_restoran)->first();

        if (!$menu) {
            $this->markTestSkipped('Belum ada menu.');
        }

        $response = $this
            ->actingAs($owner)
            ->put("/pemilik/toko/menu/{$menu->id_menu}", [
                'nama_menu' => 'Nama Menu Baru',
                'harga' => 30000,
                'deskripsi' => $menu->deskripsi,
                'id_kategori_menu' => $menu->id_kategori_menu,
                'tersedia' => 1,
            ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);

        $this->assertDatabaseHas('menu', [
            'id_menu' => $menu->id_menu,
            'nama_menu' => 'Nama Menu Baru',
        ]);
    }

    public function test_owner_bisa_toggle_menu()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();
        $restoran = Restoran::where('id_pemilik', $owner->id)->first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $menu = Menu::where('id_restoran', $restoran->id_restoran)->first();

        if (!$menu) {
            $this->markTestSkipped('Belum ada menu.');
        }

        $response = $this
            ->actingAs($owner)
            ->post("/pemilik/toko/menu/{$menu->id_menu}/toggle");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_owner_bisa_hapus_menu()
    {
        $owner = User::where('role', 'pemilik_usaha')->first();
        $restoran = Restoran::where('id_pemilik', $owner->id)->first();
        $kategori = KategoriMenu::first();

        if (!$restoran) {
            $this->markTestSkipped('Owner belum memiliki restoran.');
        }

        $menu = Menu::create([
            'id_restoran' => $restoran->id_restoran,
            'nama_menu' => 'Menu Hapus PHPUnit',
            'harga' => 10000,
            'deskripsi' => 'Testing',
            'id_kategori_menu' => $kategori?->id_kategori_menu,
            'tersedia' => 1,
        ]);

        $response = $this
            ->actingAs($owner)
            ->delete("/pemilik/toko/menu/{$menu->id_menu}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);

        $this->assertDatabaseMissing('menu', [
            'id_menu' => $menu->id_menu,
        ]);
    }
}