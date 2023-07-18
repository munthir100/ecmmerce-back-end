<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'user_type' => $this->getUserType(),
        ];
    }

    private function getUserType()
    {
        return $this->user_type_id === 1 ? 'admin' : ($this->user_type_id === 2 ? 'customer' : ($this->user_type_id === 3 ? 'seller' : null));
    }
}
