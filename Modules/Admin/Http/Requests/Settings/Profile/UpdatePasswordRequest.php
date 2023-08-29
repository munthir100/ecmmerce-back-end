<?php

namespace Modules\Admin\Http\Requests\Settings\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:6|max:255',
            'new_password' => 'required|string|min:6|max:255',
            'new_password_confirmation' => 'required|string|min:6|max:255|same:new_password',
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
