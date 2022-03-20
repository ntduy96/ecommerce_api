<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StoreControllerTest extends TestCase
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

    /**
     * Test get stores success.
     *
     * @return void
     */
    public function testGetStoresSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)->getJson('/api/store');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('total_row_count', 3)
                ->where('available_row_count', 3)
                ->where('current_page', 1)
                ->has('data', 3)
                ->etc()
        );
    }

    /**
     * Test create store success.
     *
     * @return void
     */
    public function testCreateStoreSuccess()
    {
        $data = [
            'name' => 'test'
        ];
        $token = $this->login();
        $response = $this->withToken($token)
            ->postJson('/api/store', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'is_success' => true
        ]);
        $this->assertDatabaseHas('stores', $data);
    }

    /**
     * Test get store detail success.
     *
     * @return void
     */
    public function testGetStoreDetailSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)->getJson('/api/store/1');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('id', 1)
                ->has('user_id')
                ->has('name')
                ->has('products', 3)
                ->etc()
        );
    }

    /**
     * Test update store detail success.
     *
     * @return void
     */
    public function testUpdateStoreDetailSuccess()
    {
        $data = [
            'name' => 'test_test'
        ];
        $token = $this->login();
        $response = $this->withToken($token)
            ->patchJson('/api/store/1', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'is_success' => true
        ]);
        $this->assertDatabaseHas('stores', $data);
    }

    /**
     * Test delete stores success.
     *
     * @return void
     */
    public function testDeleteStoresSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)->deleteJson('/api/store/1');

        $response->assertStatus(200);
        $response->assertJson([
            'is_success' => true
        ]);
        $this->assertDatabaseMissing('stores', ['id' => 1]);
    }
}
