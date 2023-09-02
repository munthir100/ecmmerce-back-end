<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('user_type_id', 3);
                }),
            ],
            'password' => 'required|string|min:8',
            'role' => 'nullable|string',
            'permissions' => [
                'nullable',
                'array',
                'distinct',
                Rule::in(DB::table('permissions')->pluck('name')->toArray()),
            ],
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
