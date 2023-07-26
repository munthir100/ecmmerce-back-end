<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class MessageResponse implements Responsable
{
    public function __construct(
        public string $message = 'Success!',
        public array|JsonResource $data = [],
        public int $statusCode = 200,
    ) {
    }

    public function toResponse($request): Response
    {
        return response()->json([
            'message' => $this->message,
            'status' => $this->statusCode,
            'data' => $this->data ?? [],
        ], $this->statusCode);
    }
}
