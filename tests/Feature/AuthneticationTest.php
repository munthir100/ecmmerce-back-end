<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Modules\Acl\Entities\User;
use Database\Factories\UserFactory;
use Modules\Shipping\Entities\Country;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthneticationTest extends TestCase
{
    
    public function test_registration_for_admin()
    {
        Country::factory()->count(10)->create();
        $data = [
            'name' => Str::random(),
            'email' => Str::random() . '@example.com',
            'phone' => mt_rand(100000000, 999999999),
            'password' => 'password',
            'store_name' => Str::random(),
            'link' => Str::random(),
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
        $response = $this->post('/api/admin/register', $data);
        $response->assertStatus(200);
    }
    public function test_for_admin_login(){
        $data = [
            'username' => 'a@a.com',
            'password' => 'aaa',
        ];
        $response = $this->post('/api/admin/login', $data);
        $response->assertStatus(200);
        return $response->json('data.token');
    }
}
