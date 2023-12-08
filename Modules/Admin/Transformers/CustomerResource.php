<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'description' => $this->description,
            'number_of_orders' => $this->number_of_orders,
            'city_id' => $this->city_id,
        ];
    }
}
