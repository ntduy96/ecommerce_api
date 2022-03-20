<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testGetUserInfoUnauthorized()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function testGetUserInfoSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('id')
                ->has('email')
                ->has('stores')
                ->has('products')
                ->missing('password')
                ->etc()
        );
    }
}
