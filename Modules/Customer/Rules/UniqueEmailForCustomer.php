<?php

namespace Modules\Customer\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Acl\Entities\User;

class UniqueEmailForCustomer implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $EmailIsExist = DB::table('users')
            ->where('email', $value)
            ->where('user_type_id', 2)
            ->count();

            if($EmailIsExist )



        if ($EmailIsExist) {
            $fail('The :attribute is already exist for another customer.');
        }
    }
}
