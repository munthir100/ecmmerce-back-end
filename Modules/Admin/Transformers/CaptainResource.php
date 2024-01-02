<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CaptainResource extends JsonResource
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
            'name' => $this->name,
            'shipping_cost' => $this->shipping_cost,
            'expected_time_shipping' => $this->expected_time_shipping,
            'cash_on_delivery' => $this->cash_on_delivery,
            'cash_on_delivery_cost' => $this->cash_on_delivery_cost,
            'store_id' => $this->store_id,
            'cities' => CityResource::collection($this->cities),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
