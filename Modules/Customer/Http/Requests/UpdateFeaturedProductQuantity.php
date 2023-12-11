<?php

namespace Modules\Customer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeaturedProductQuantity extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_option_id' => 'required|integer',
            'product_option_value_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ];
    }

    function validateQuantity($availableQuantity)
    {
        return $this->validate([
            'quantity' => 'max:' . $availableQuantity,
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
