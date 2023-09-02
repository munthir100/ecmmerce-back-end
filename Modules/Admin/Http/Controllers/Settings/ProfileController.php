<?php

namespace Modules\Admin\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Transformers\UserResource;
use Modules\Admin\Http\Requests\Settings\Profile\UpdatePasswordRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        return $this->responseSuccess(data: $userData);
    }


    public function update(Request $request)
    {
        $user = $request->user();

        $data = $this->validateProfileData($user);
        return $data;
        $user->update($data);

        return $this->responseSuccess('user data updated', new UserResource($user), 200);
    }

    public function changePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $vaildPassword = Hash::check($data['password'], $user->password);

        if (!$vaildPassword) {
            return $this->responseConflictError('invaild password');
        }
        
        $user->update(['password' => Hash::make($data['new_password'])]);

        return $this->responseSuccess('password updated', new UserResource($user), 200);
    }




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
                            ->where('id', '!=', $user->id);
                            // ->whereNull('deleted_at');
                    }),
                ],
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('users', 'phone')->where(function ($query) use ($user) {
                        return $query->where('user_type_id', 1)
                            ->where('id', '!=', $user->id);
                            // ->whereNull('deleted_at');
                    }),
                ],
            ]);
        }
}
