<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Http\Responses\PaginationResponse;
use App\Models\Store;
use App\Models\User;
use Exception;

class StoreService
{
    /**
     * The store repository instance.
     *
     * @var \App\Models\Store
     */
    protected $store;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Get stores.
     *
     * @param array $filter
     * @param integer $size
     * @return array
     */
    public function getStores(array $filter, int $size): array
    {
        try {
            $paginator = $this->store::where($filter)->paginate($size);

            return (new PaginationResponse($paginator))->toArray();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to get stores',
                $e->getMessage()
            );
        }
    }

    /**
     * Create store.
     *
     * @param User $user
     * @param array $data
     * @return array
     */
    public function createStore(User $user, array $data): array
    {
        $store = new Store($data);
        $store->user_id = $user->id;
        $result = false;
        try {
            $result = $store->save();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to create store',
                $e->getMessage()
            );
        }

        return [
            'is_success' => $result,
            'id' => $store->id,
        ];
    }

    /**
     * Get store detail.
     *
     * @param integer $id
     * @return array
     */
    public function getStoreDetail(int $id): array
    {
        try {
            $store = Store::with('products')->find($id);
            if (!$store) {
                throw new Exception('Store does not exist');
            }
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to get store detail',
                $e->getMessage()
            );
        }

        return $store->toArray();
    }

    /**
     * Update store detail.
     *
     * @param User $user
     * @param integer $id
     * @param array $data
     * @return array
     */
    public function updateStoreDetail(User $user, int $id, array $data): array
    {
        $result = false;
        try {
            $store = $user->stores()->find($id);
            if (!$store) {
                throw new ApiException('User can only modify their Stores belonging to them');
            }
            $store->fill($data);
            $result = $store->save();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to update store',
                $e->getMessage()
            );
        }

        return [
            'is_success' => $result,
            'id' => $id,
        ];
    }

    /**
     * Delete store.
     *
     * @param User $user
     * @param integer $id
     * @return array
     */
    public function deleteStore(User $user, int $id): array
    {
        $result = false;
        try {
            $store = $user->stores()->find($id);
            if (!$store) {
                throw new Exception('User can only modify their Stores belonging to them');
            }

            $result = $store->delete();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to delete store',
                $e->getMessage()
            );
        }

        return [
            'is_success' => $result,
            'id' => $id,
        ];
    }
}
