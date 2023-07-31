<?php

namespace App\Actions;

use Illuminate\Validation\Rule;

class ValidateAdminProfile
{
    public function validateProfileData()
    {
        function validateProfileData($user)
        {
            return request()->validate([
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
    }
}
