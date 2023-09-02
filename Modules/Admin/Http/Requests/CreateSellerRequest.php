<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSellerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'permissions' => 'nullable|array',
        ];
    }


    public function validateEmail($admin, $store)
    {
        $this->validate([
            'email' => [
                Rule::unique('users')->where(function ($query) use ($store, $admin) {
                    
                    $query->where('store_id', $store->id)
                        ->where('admin_id', $admin->id);
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
