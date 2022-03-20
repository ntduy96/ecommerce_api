<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    const DEFAULT_PAGING_SIZE = 100;

    /**
     * The user repository instance.
     *
     * @var \App\Services\ProductService
     */
    protected $products;
 
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\ProductService  $products
     * @return void
     */
    public function __construct(ProductService $products)
    {
        $this->products = $products;
    }

    /**
     * Display a listing of the product.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchProductRequest $request)
    {
        // Retrieve the validated input data
        $data = $request->validated();
        $size = $request->get('size', self::DEFAULT_PAGING_SIZE);

        // User can see only their product
        return $this->products->getProducts($data, $size);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        // Retrieve the validated input data
        $data = $request->validated();

        return $this->products->createProduct($request->user(), $data);
    }

    /**
     * Display the specified product.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->products->getProductDetail($id);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        // Retrieve the validated input data
        $data = $request->validated();

        return $this->products->updateProductDetail($request->user(), $id, $data);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->products->deleteProduct($request->user(), $id);
    }
}
