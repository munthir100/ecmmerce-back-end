<?php

namespace Modules\Admin\Http\Controllers\Settings;

use App\Actions\ValidateAdminProfile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Http\Requests\Settings\Profile\UpdatePasswordRequest;
use Modules\Admin\Transformers\UserResource;

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

        return new MessageResponse(data: $userData, statusCode: 200);
    }


    public function update(Request $request, ValidateAdminProfile $profileService)
    {
        $user = $request->user();

        $data = $profileService->validateProfileData($user);

        $user->update($data);

        return new MessageResponse('user data updated', new UserResource($user), 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $vaildPassword = Hash::check($data['password'], $user->password);
        if (!$vaildPassword) {
            return new MessageResponse('invaild password', statusCode: 404);
        }
        $user->update(['password' => Hash::make($data['new_password'])]);

        return new MessageResponse('password updated', new UserResource($user), 200);
    }
}
