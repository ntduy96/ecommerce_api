<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;

class PaginationResponse implements Arrayable
{
    public $totalRowCount;
    public $availableRowCount;
    public $currentPage;
    public $data;

    /**
     * Constructor.
     *
     * @param LengthAwarePaginator $paginator
     */
    public function __construct(LengthAwarePaginator $paginator) {
        $this->totalRowCount = $paginator->total();
        $this->availableRowCount = count($paginator->items());
        $this->currentPage = $paginator->currentPage();
        $this->data = $paginator->items();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() {
        return [
            'total_row_count' => $this->totalRowCount,
            'available_row_count' => $this->availableRowCount,
            'current_page' => $this->currentPage,
            'data' => $this->data,
        ];
    }
}
