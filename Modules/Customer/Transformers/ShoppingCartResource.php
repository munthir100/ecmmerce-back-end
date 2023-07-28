<?php

namespace Modules\Customer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            $additionalPrice = $item->pivot->product_option_value ? $item->pivot->additional_price : 0;
            $subtotalPrice = ($item->price + $additionalPrice) * $item->pivot->quantity;

            return [
                'item' => $item->name,
                'quantity' => $item->pivot->quantity,
                'product_option' => $item->pivot->product_option,
                'product_option_value' => $item->pivot->product_option_value,
                'additional_price' => $additionalPrice,
                'subtotal_price' => $subtotalPrice,
            ];
        });

        // Calculate the total price as the sum of all subtotals
        $totalPrice = $items->sum('subtotal_price');

        return [
            'id' => $this->id,
            'products' => $items,
            'total_price' => $totalPrice,
        ];
    }
}
