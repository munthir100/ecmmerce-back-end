<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'promocode' => 'required',
            'discount_type' => 'required|in:percentage,fixed',
            'value' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($this->input('discount_type') === 'percentage', 'max:100'),
            ],
            'value' => 'required|numeric|min:0',
            'discount_end_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'exclude_discounted_products' => 'required|boolean',
            'minimum_purchase' => 'required|numeric|min:0',
            'total_usage_times' => 'required|integer|min:1',
            'usage_per_customer' => 'required|integer|min:1',
            'is_active' => 'boolean|sometimes'

        ];
    }

    function validatePromocodeIsUniqueInStore($store, $couponId)
    {
        return $this->validate([
            'promocode' => Rule::unique('coupons', 'promocode')->where(function ($query) use ($store, $couponId) {
                return $query->where('store_id', $store->id)
                    ->where('id', '!=', $couponId);
            })
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
