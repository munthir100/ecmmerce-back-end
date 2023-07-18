<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
            'quantity' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $product = $this->route('product');
                    if (!$product->unspecifiedQuantity && $value > $product->quantity) {
                        $fail('The requested quantity exceeds the available quantity for this product');
                    }
                },
            ],
        ];
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
