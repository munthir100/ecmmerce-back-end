<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Response;

class CouponRequest extends FormRequest
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
                'required',
                'date',
                'after_or_equal:today',
            ],
            'exclude_discounted_products' => 'required|boolean',
            'minimum_purchase' => 'required|numeric|min:0',
            'total_usage_times' => 'required|integer|min:1',
            'usage_per_customer' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    function validatePromocodeIsUniqueInStore($store)
    {
        return $this->validate([
            'promocode' => Rule::unique('coupons', 'promocode')->where(function ($query) use ($store) {
                return $query->where('store_id', $store->id);
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
