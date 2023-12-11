<?php

namespace Modules\Customer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $products = $this->products->map(function ($product) {
            $additionalPrice = $product->pivot->product_option_value ? $product->pivot->additional_price : 0;
            $subtotalPrice = ($product->price + $additionalPrice) * $product->pivot->quantity;
            $item = $this->items()->where('product_id',$product->id)->first();
            
            return [
                'id' => $item->id,
                'product_name' => $product->name,
                'quantity' => $product->pivot->quantity,
                'product_option' => $product->pivot->product_option,
                'product_option_value' => $product->pivot->product_option_value,
                'additional_price' => $additionalPrice,
                'subtotal_price' => $subtotalPrice,
            ];
        });

        // Calculate the total price as the sum of all subtotals
        $totalPrice = $products->sum('subtotal_price');

        return [
            'id' => $this->id,
            'items' => $products,
            'total_price' => $totalPrice,
        ];
    }
}
