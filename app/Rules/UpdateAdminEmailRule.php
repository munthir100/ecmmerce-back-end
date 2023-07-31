<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateAdminEmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();
        $EmailIsExist = DB::table('users')
            ->where('email', $value)
            ->where('user_type_id', 1)
            ->where('id', '!=', $user->id)
            ->whereNull('deleted_at')
            ->first();


        if ($EmailIsExist) {
            $fail('The :attribute is already exist for another admin.');
        }
    }
}
