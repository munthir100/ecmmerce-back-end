<?php

namespace Modules\Customer\Transformers;

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
            'customer_id' => $this->customer_id,
            'store_id' => $this->store_id,
            'captain_id' => $this->captain_id,
            'location_id' => $this->location_id,
            'status' => $this->status_id,
            'total_price' => $this->total_price,
            'payment_type' => $this->payment_type,
        ];
    }
}
