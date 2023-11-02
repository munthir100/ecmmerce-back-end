<?php

namespace Modules\Admin\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Transformers\UserResource;
use Modules\Admin\Http\Requests\Settings\Profile\UpdatePasswordRequest;
use Modules\Admin\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $user = request()->authenticated_user;
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        return $this->responseSuccess(data: $userData);
    }


    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validateProfileData($user);
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





}
