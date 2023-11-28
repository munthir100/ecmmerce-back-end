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
            'promocode' => 'sometimes',
            'discount_type' => 'sometimes|in:percentage,fixed',
            'value' => [
                'sometimes',
                'numeric',
                function ($attribute, $value, $fail) {
                    $discountType = $this->input('discount_type');

                    if ($discountType == 'percentage') {
                        // If discount type is percentage, value must be between 0 and 100
                        if ($value < 0 || $value > 100) {
                            $fail("The $attribute must be between 0 and 100 when the discount type is percentage.");
                        }
                    } elseif ($discountType == 'fixed') {
                        // If discount type is fixed, value must be greater than 0
                        if ($value <= 0) {
                            $fail("The $attribute must be greater than 0 when the discount type is fixed.");
                        }
                    }
                },
            ],
            'discount_end_date' => [
                'sometimes',
                'date',
                'after_or_equal:today',
            ],
            'exclude_discounted_products' => 'sometimes|boolean',
            'minimum_purchase' => 'sometimes|numeric|min:0',
            'total_usage_times' => 'sometimes|integer|min:1',
            'usage_per_customer' => 'sometimes|integer|min:1',
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
