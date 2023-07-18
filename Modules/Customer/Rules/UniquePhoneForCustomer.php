<?php

namespace Modules\Customer\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePhoneForCustomer implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $PhoneIsExist = DB::table('users')
            ->where('phone', $value)
            ->where('user_type_id', 2)
            ->count();

        if ($PhoneIsExist) {
            $fail('The :attribute is already exist for another customer.');
        }
    }
}
