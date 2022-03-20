<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchStoreRequest;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    const DEFAULT_PAGING_SIZE = 100;

    /**
     * The user repository instance.
     *
     * @var \App\Services\StoreService
     */
    protected $stores;
 
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\StoreService  $stores
     * @return void
     */
    public function __construct(StoreService $stores)
    {
        $this->stores = $stores;
    }

    /**
     * Display a listing of the store.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchStoreRequest $request)
    {
        // Retrieve the validated input data
        $data = $request->validated();
        $size = $request->get('size', self::DEFAULT_PAGING_SIZE);

        // User can see only their store
        return $this->stores->getStores($data, $size);
    }

    /**
     * Store a newly created store in storage.
     *
     * @param  \App\Http\Requests\StoreStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreRequest $request)
    {
        // Retrieve the validated input data
        $data = $request->validated();

        return $this->stores->createStore($request->user(), $data);
    }

    /**
     * Display the specified store.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->stores->getStoreDetail($id);
    }

    /**
     * Update the specified store in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreRequest  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStoreRequest $request, $id)
    {
        // Retrieve the validated input data
        $data = $request->validated();

        return $this->stores->updateStoreDetail($request->user(), $id, $data);
    }

    /**
     * Remove the specified store from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->stores->deleteStore($request->user(), $id);
    }
}
