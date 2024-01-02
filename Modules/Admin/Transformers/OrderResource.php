<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->user->name,
            'date' => $this->created_at,
            'payment_type' => $this->payment_type,
            'shipping' => $this->captain->name,
            'total_price' => $this->total_price,
            'status' => $this->status,
        ];
    }
}
