<?php

namespace Modules\Customer\Transformers;

use Modules\Admin\Transformers\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartItemResource extends JsonResource
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
            'quantity' => $this->quantity,
            'store_id' => $this->store_id,
            'product' => $this->product->name,
            'product_price' => $this->product->price,
            'product_option' => $this->product_option,
            'product_option_value' => $this->product_option_value,
            'additional_price' => $this->additional_price,
        ];
    }
}
