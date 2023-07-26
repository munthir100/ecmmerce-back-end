<?php

namespace Modules\Customer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\Transformers\ProductResource;

class shoppingCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $items = $this->products->map(function ($item) {
            return [
                'item' => $item->name,
                'quantity' => $item->pivot->quantity,
                'product_option' => $item->pivot->product_option,
                'product_option_value' => $item->pivot->product_option_value,
            ];
        });

        return [
            'id' => $this->id,
            'products' => $items,
            'total_price' => $this->TotalPrice
        ];
    }
}
