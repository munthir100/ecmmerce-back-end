<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    function validateProfileData($user)
    {
        return $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->where(function ($query) use ($user) {
                    return $query->where('user_type_id', 1)
                        ->where('id', '!=', $user->id)
                        ->whereNull('deleted_at');
                }),
            ],
            'phone' => [
                'required',
                'string',
                Rule::unique('users', 'phone')->where(function ($query) use ($user) {
                    return $query->where('user_type_id', 1)
                        ->where('id', '!=', $user->id)
                        ->whereNull('deleted_at');
                }),
            ],
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
