<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_login_page_can_be_accessed()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_register_page_can_be_accessed()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_admin_login_success()
    {
        $response = $this->post('/login',[
            'email'=>'admin@halalfood.com',
            'password'=>'admin123'
        ]);

        $response->assertRedirect('/admin/index');
    }

    public function test_owner_login_success()
    {
        $response = $this->post('/login',[
            'email'=>'suardi@gmail.com',
            'password'=>'123456'
        ]);

        $response->assertRedirect('/pemilik/dashboard');
    }

    public function test_pencari_login_success()
    {
        $response = $this->post('/login',[
            'email'=>'saskia@gmail.com',
            'password'=>'123456'
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_login_failed_wrong_password()
    {
        $response = $this->from('/login')->post('/login',[
            'email'=>'admin@halalfood.com',
            'password'=>'salahpassword'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }
}