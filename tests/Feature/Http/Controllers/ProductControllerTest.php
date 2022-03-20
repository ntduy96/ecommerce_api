<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductControllerTest extends TestCase
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
     * Test get products success.
     *
     * @return void
     */
    public function testGetProductsSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)
            ->getJson('/api/product?size=3');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('total_row_count', 9)
                ->where('available_row_count', 3)
                ->where('current_page', 1)
                ->has('data', 3)
                ->etc()
        );
    }

    /**
     * Test create product success.
     *
     * @return void
     */
    public function testCreateProductSuccess()
    {
        $data = [
            'name' => 'test',
            'price' => '10.00',
            'quantity' => '99',
            'store_id' => 1
        ];
        $token = $this->login();
        $response = $this->withToken($token)
            ->postJson('/api/product', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'is_success' => true
        ]);
        $this->assertDatabaseHas('products', $data);
    }

    /**
     * Test get product detail success.
     *
     * @return void
     */
    public function testGetProductDetailSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)->getJson('/api/product/1');

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->where('id', 1)
                ->has('name')
                ->has('store_id')
                ->has('store')
                ->etc()
        );
    }

    /**
     * Test update product detail success.
     *
     * @return void
     */
    public function testUpdateProductDetailSuccess()
    {
        $data = [
            'name' => 'test_test',
            'price' => '11.00',
            'quantity' => '999',
            'store_id' => 1
        ];
        $token = $this->login();
        $response = $this->withToken($token)
            ->patchJson('/api/product/1', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'is_success' => true
        ]);
        $this->assertDatabaseHas('products', $data);
    }

    /**
     * Test delete products success.
     *
     * @return void
     */
    public function testDeleteProductsSuccess()
    {
        $token = $this->login();
        $response = $this->withToken($token)->deleteJson('/api/product/1');

        $response->assertStatus(200);
        $response->assertJson([
            'is_success' => true
        ]);
        $this->assertDatabaseMissing('products', ['id' => 1]);
    }
}
