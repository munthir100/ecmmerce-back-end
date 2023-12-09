<?php

namespace Modules\Admin\Http\Requests;

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
