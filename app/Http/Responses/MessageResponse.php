<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResponse implements Responsable
{
    public function __construct(
        public string $message = 'Success!',
        public array|JsonResource $data = [],
        public $statusCode = 200,
        public bool $sendDataAsNull = false,
    ) {
    }

    public function toResponse($request)
    {
        $meta = [];
        $key = collect($this->data)->keys()->first();
        $firstItem = $this->data[$key] ?? null;
        if (isset($firstItem->resource) && $firstItem->resource instanceof LengthAwarePaginator) {
            $paginationMeta = $this->data[$key]->resource->toArray();
            $meta = [
                'total' => $paginationMeta['total'],
                'per_page' => request('per_page') ? request('per_page') : config('api-tool-kit.default_pagination_number'),
                'current_page' => $paginationMeta['current_page'],
                'last_page' => $paginationMeta['last_page'],
            ];
        }

        return response()->json([
            'message' => $this->message,
            'status' => $this->statusCode,
            'data' => $this->data ? $this->data : ($this->sendDataAsNull ? null : []),
            ...($meta ? ['meta' => $meta] : []),
        ], $this->statusCode);
    }
}