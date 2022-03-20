<?php

namespace Tests\Unit;

use App\Exceptions\ApiException;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

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
        // Input
        $filter = [];
        $size = 3;

        // Execute
        $service = new ProductService(new Product);
        $result = $service->getProducts($filter, $size);

        // Assert
        $this->assertEquals($result['total_row_count'], 9);
        $this->assertEquals($result['available_row_count'], 3);
        $this->assertEquals($result['current_page'], 1);
        $this->assertEquals(count($result['data']), 3);
    }

    /**
     * Test get products failure.
     *
     * @return void
     */
    public function testGetProductsFailure()
    {
        // Input
        $filter = [];
        $size = 10;

        // Mock
        $mock = $this->mock(Product::class);
        $mock->shouldReceive('with->paginate')->andThrow(new Exception());
        $service = new ProductService($mock);
        

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to get products');

        // Execute
        $service->getProducts($filter, $size);
    }

    /**
     * Test create product success.
     *
     * @return void
     */
    public function testCreateProductSuccess()
    {
        // Input
        $user = new User();
        $user->id = 1;
        $data = [
            'name' => 'test',
            'price' => '10.00',
            'quantity' => '99',
            'store_id' => 1
        ];

        // Execute
        $service = new ProductService(new Product);
        $result = $service->createProduct($user, $data);

        // Assert
        $this->assertTrue($result['is_success']);
        $this->assertNotEmpty($result['id']);
    }

    /**
     * Test create product failure.
     *
     * @return void
     */
    public function testCreateProductFailure()
    {
        // Input
        $user = new User();
        $user->id = 9999; // FOREIGN KEY constraint failed due to user_id does not exist
        $data = [
            'name' => 'test',
            'price' => '10.00',
            'quantity' => '99',
            'store_id' => 1
        ];

        // Mock
        $service = new ProductService(new Product);

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to create product');

        // Execute
        $service->createProduct($user, $data);
    }

    /**
     * Test get product detail success.
     *
     * @return void
     */
    public function testGetProductDetailSuccess()
    {
        // Input
        $id = 1;

        // Execute
        $service = new ProductService(new Product);
        $result = $service->getProductDetail($id);

        // Assert
        $this->assertNotEmpty($result['name']);
    }

    /**
     * Test get product detail failure.
     *
     * @return void
     */
    public function testGetProductDetailFailure()
    {
        // Input
        $id = 10;

        // Mock
        $mock = $this->mock(Product::class);
        $mock->shouldReceive('with->find')->andThrow(new Exception());
        $service = new ProductService($mock);
        

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to get product detail');

        // Execute
        $service->getProductDetail($id);
    }

    /**
     * Test update product success.
     *
     * @return void
     */
    public function testUpdateProductDetailSuccess()
    {
        // Input
        $id = 1;
        $user = new User();
        $user->id = 1;
        $data = [
            'name' => 'test',
            'price' => '10.00',
            'quantity' => '99',
        ];

        // Execute
        $service = new ProductService(new Product);
        $result = $service->updateProductDetail($user, $id, $data);

        // Assert
        $this->assertTrue($result['is_success']);
        $this->assertNotEmpty($result['id']);
    }

    /**
     * Test update product failure.
     *
     * @return void
     */
    public function testUpdateProductDetailFailure()
    {
        // Input
        $id = 100;
        $user = new User();
        $user->id = 1; // product does not belong to this user
        $data = [
            'name' => 'test',
            'price' => '10.00',
            'quantity' => '99',
        ];

        // Mock
        $service = new ProductService(new Product);

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to update product');

        // Execute
        $service->updateProductDetail($user, $id, $data);
    }

    /**
     * Test delete product success.
     *
     * @return void
     */
    public function testDeleteProductSuccess()
    {
        // Input
        $user = new User();
        $user->id = 1;
        $id = 1;

        // Execute
        $service = new ProductService(new Product);
        $result = $service->deleteProduct($user, $id);

        // Assert
        $this->assertTrue($result['is_success']);
    }

    /**
     * Test delete product failure.
     *
     * @return void
     */
    public function testDeleteProductFailure()
    {
        // Input
        $user = new User();
        $user->id = 1;  // product does not belong to this user
        $id = 10;

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to delete product');

        // Execute
        $service = new ProductService(new Product);
        $service->deleteProduct($user, $id);
    }
}
