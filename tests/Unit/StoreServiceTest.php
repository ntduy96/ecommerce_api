<?php

namespace Tests\Unit;

use App\Exceptions\ApiException;
use App\Models\Store;
use App\Models\User;
use App\Services\StoreService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreServiceTest extends TestCase
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
     * Test get stores success.
     *
     * @return void
     */
    public function testGetStoresSuccess()
    {
        // Input
        $filter = [];
        $size = 10;

        // Execute
        $service = new StoreService(new Store);
        $result = $service->getStores($filter, $size);

        // Assert
        $this->assertEquals($result['total_row_count'], 3);
        $this->assertEquals($result['available_row_count'], 3);
        $this->assertEquals($result['current_page'], 1);
        $this->assertEquals(count($result['data']), 3);
    }

    /**
     * Test get stores failure.
     *
     * @return void
     */
    public function testGetStoresFailure()
    {
        // Input
        $filter = [];
        $size = 10;

        // Mock
        $mock = $this->mock(Store::class);
        $mock->shouldReceive('with->paginate')->andThrow(new Exception());
        $service = new StoreService($mock);


        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to get stores');

        // Execute
        $service->getStores($filter, $size);
    }

    /**
     * Test create store success.
     *
     * @return void
     */
    public function testCreateStoreSuccess()
    {
        // Input
        $user = new User();
        $user->id = 1;
        $data = [
            'name' => 'test'
        ];

        // Execute
        $service = new StoreService(new Store);
        $result = $service->createStore($user, $data);

        // Assert
        $this->assertTrue($result['is_success']);
        $this->assertNotEmpty($result['id']);
    }

    /**
     * Test create store failure.
     *
     * @return void
     */
    public function testCreateStoreFailure()
    {
        // Input
        $user = new User();
        $user->id = 9999; // FOREIGN KEY constraint failed due to user_id does not exist
        $data = [
            'name' => 'test'
        ];

        // Mock
        $service = new StoreService(new Store);

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to create store');

        // Execute
        $service->createStore($user, $data);
    }

    /**
     * Test get store detail success.
     *
     * @return void
     */
    public function testGetStoreDetailSuccess()
    {
        // Input
        $id = 1;

        // Execute
        $service = new StoreService(new Store);
        $result = $service->getStoreDetail($id);

        // Assert
        $this->assertNotEmpty($result['name']);
    }

    /**
     * Test get store detail failure.
     *
     * @return void
     */
    public function testGetStoreDetailFailure()
    {
        // Input
        $id = 10;

        // Mock
        $mock = $this->mock(Store::class);
        $mock->shouldReceive('with->find')->andThrow(new Exception());
        $service = new StoreService($mock);


        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to get store detail');

        // Execute
        $service->getStoreDetail($id);
    }

    /**
     * Test update store success.
     *
     * @return void
     */
    public function testUpdateStoreDetailSuccess()
    {
        // Input
        $id = 1;
        $user = new User();
        $user->id = 1;
        $data = [
            'name' => 'test'
        ];

        // Execute
        $service = new StoreService(new Store);
        $result = $service->updateStoreDetail($user, $id, $data);

        // Assert
        $this->assertTrue($result['is_success']);
        $this->assertNotEmpty($result['id']);
    }

    /**
     * Test update store failure.
     *
     * @return void
     */
    public function testUpdateStoreDetailFailure()
    {
        // Input
        $id = 100;
        $user = new User();
        $user->id = 1; // store does not belong to this user
        $data = [
            'name' => 'test'
        ];

        // Mock
        $service = new StoreService(new Store);

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to update store');

        // Execute
        $service->updateStoreDetail($user, $id, $data);
    }

    /**
     * Test delete store success.
     *
     * @return void
     */
    public function testDeleteStoreSuccess()
    {
        // Input
        $user = new User();
        $user->id = 1;
        $id = 1;

        // Execute
        $service = new StoreService(new Store);
        $result = $service->deleteStore($user, $id);

        // Assert
        $this->assertTrue($result['is_success']);
    }

    /**
     * Test delete store failure.
     *
     * @return void
     */
    public function testDeleteStoreFailure()
    {
        // Input
        $user = new User();
        $user->id = 1;  // store does not belong to this user
        $id = 10;

        // Expect
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Failed to delete store');

        // Execute
        $service = new StoreService(new Store);
        $service->deleteStore($user, $id);
    }
}
