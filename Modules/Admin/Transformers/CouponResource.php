<?php

namespace Modules\Admin\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'promocode' => $this->promocode,
            'discount_type' => $this->discount_type,
            'value' => $this->value,
            'discount_end_date' => $this->discount_end_date,
            'exclude_discounted_products' => $this->exclude_discounted_products,
            'minimum_purchase' => $this->minimum_purchase,
            'total_usage_times' => $this->total_usage_times,
            'usage_per_customer' => $this->usage_per_customer,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
