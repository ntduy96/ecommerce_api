<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Http\Responses\PaginationResponse;
use App\Models\Product;
use App\Models\User;
use Exception;

class ProductService
{
    /**
     * The product repository instance.
     *
     * @var \App\Models\Product
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get products.
     *
     * @param array $filter
     * @param integer $size
     * @return array
     */
    public function getProducts(array $filter, int $size): array
    {
        try {
            $paginator = $this->product::where($filter)->paginate($size);

            return (new PaginationResponse($paginator))->toArray();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to get products',
                $e->getMessage()
            );
        }
    }

    /**
     * Create product.
     *
     * @param User $user
     * @param array $data
     * @return array
     */
    public function createProduct(User $user, array $data): array
    {
        $result = false;
        try {
            $store = $user->stores()->find($data['store_id']);
            if (!$store) {
                throw new ApiException('User can only add Product to their Stores belonging to them');
            }
            $product = new Product($data);
            $result = $product->save();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to create product',
                $e->getMessage()
            );
        }

        return [
            'is_success' => $result,
            'id' => $product->id,
        ];
    }

    /**
     * Get product detail.
     *
     * @param integer $id
     * @return array
     */
    public function getProductDetail(int $id): array
    {
        try {
            $product = Product::with('store')->find($id);
            if (!$product) {
                throw new Exception('Product does not exist');
            }
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to get product detail',
                $e->getMessage()
            );
        }

        return $product->toArray();
    }

    /**
     * Update product detail.
     *
     * @param User $user
     * @param integer $id
     * @param array $data
     * @return array
     */
    public function updateProductDetail(User $user, int $id, array $data): array
    {
        $result = false;
        try {
            $product = $user->products()->find($id);
            if (!$product) {
                throw new ApiException('User can only modify their Products belonging to them');
            }
            $product->fill($data);
            $result = $product->save();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to update product',
                $e->getMessage()
            );
        }

        return [
            'is_success' => $result,
            'id' => $id,
        ];
    }

    /**
     * Delete product.
     *
     * @param User $user
     * @param integer $id
     * @return array
     */
    public function deleteProduct(User $user, int $id): array
    {
        $result = false;
        try {
            $product = $user->products()->find($id);
            if (!$product) {
                throw new Exception('User can only modify their Products belonging to them');
            }

            $result = $product->delete();
        } catch (Exception $e) {
            throw new ApiException(
                'Failed to delete product',
                $e->getMessage()
            );
        }

        return [
            'is_success' => $result,
            'id' => $id,
        ];
    }
}
