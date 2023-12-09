<?php

namespace Modules\Admin\Http\Requests;

use Modules\Shipping\Entities\Country;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\Rules\UniqueEmailForAdmin;
use Modules\Admin\Rules\UniquePhoneForAdmin;
use Illuminate\Validation\ValidationException;

class AdminRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => ['required', new UniqueEmailForAdmin],
            'country_id' => 'required',
            'phone' => [
                'required', 'numeric', new UniquePhoneForAdmin,
            ],
            'password' => 'required',
            'store_name' => 'required|string',
            'link' => 'required|unique:stores',
        ];
    }

    function ValidPhoneForCountry($value, Country $country): void
    {
        $phoneRegex = sprintf('/^%s(?P<digits>\d{%d})$/', $country->phone_code, $country->phone_digits_number);
    
        if (!preg_match($phoneRegex, $value, $matches)) {
            throw ValidationException::withMessages([
                'phone' => ["The phone number for {$country->name} must start with {$country->phone_code} and have {$country->phone_digits_number} digits."],
            ]);
        }
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
