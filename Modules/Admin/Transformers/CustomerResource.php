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
            'birth_date' => $this->birth_date ?? 'N\A',
            'gender' => $this->gender == 0 ? 'male' : 'female',
            'description' => $this->description ?? 'N\A',
            'number_of_orders' => $this->number_of_orders,
            'city' => $this->city ? $this->city->name : 'N\A',
        ];
    }
}
