<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GenericCollection extends ResourceCollection
{
    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     * @param string $collects
     */
    public function __construct($resource, $collects)
    {
        $this->collects = $collects;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'has_pages' => $this->hasPages(),
                'first_page' => 1,
                'last_page' => $this->lastPage(),
                'prev' => $this->previousPageNumber(),
                'current_page' => $this->currentPage(),
                'next' => $this->nextPageNumber(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }



    protected function nextPageNumber()
    {
        return $this->resource->currentPage() < $this->resource->lastPage() ? $this->resource->currentPage() + 1 : null;
    }

    protected function previousPageNumber()
    {
        return $this->resource->currentPage() > 1 ? $this->resource->currentPage() - 1 : null;
    }
}
