<?php

namespace Modules\Admin\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Acl\Entities\User;
use Modules\Admin\Entities\Admin;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Rules\UniqueEmailForAdmin;
use Modules\Acl\Transformers\AuthenticatedUserResource;
use Modules\Admin\Rules\UniquePhoneForAdmin;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $username = $data['username'];
        $password = $data['password'];

        $user = User::where(function ($query) use ($username) {
            $query->where('user_type_id', 1)
                ->where('email', $username)
                ->orWhere('phone', $username);
        })->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return new MessageResponse(message: 'user not found');
        }

        $token = $user->createToken('token')->plainTextToken;

        return new MessageResponse(message: 'login successfull', data: ['token' => $token], statusCode: 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return new MessageResponse('Successfully logged out');
    }



    function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => ['required', new UniqueEmailForAdmin],
            'phone' => ['required','numeric', new UniquePhoneForAdmin],
            'password' => 'required',
            'store_name' => 'required|string',
            'link' => 'required|unique:stores',
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'user_type_id' => 1,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'country_id' => $data['country_id']
            ]);
            $admin = $user->admin()->create([
                //only user_id
            ]);
            $store = $admin->store()->create([
                'user_id' => $user->id,
                'name' => $data['store_name'],
                'link' => $data['link'],
                'store_theme_id' => 1
            ]);

            DB::commit();

            $jsonData = [
                'user' => $user,
                'admin' => $admin,
                'store' => $store,
                'token' => $user->createToken('accessToken')->plainTextToken
            ];

            return new MessageResponse(message: 'Registration success', data: $jsonData, statusCode: 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Handle the exception or throw it further
            Log::error('Registration failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
