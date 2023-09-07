<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Acl\Entities\UserType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\AdminRegisterService;
use App\Http\Responses\MessageResponse;
use Modules\Admin\Http\Requests\LoginRequest;
use Modules\Admin\Http\Requests\AdminRegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $credentials = $request->only(['username', 'password']);
        $username = $credentials['username'];

        $user = User::where(function ($query) use ($username) {
            $query->where(function ($query) use ($username) {
                $query->where('email', $username)
                    ->orWhere('phone', $username);
            })->where('user_type_id', UserType::ADMIN)

                ->orWhere(function ($query) use ($username) {
                    $query->where('email', $username);
                })->where('user_type_id', UserType::SELLER);
        })->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return new MessageResponse(message: 'User not found', statusCode: 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return new MessageResponse(message: 'Login successful', data: ['token' => $token], statusCode: 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return new MessageResponse('Successfully logged out');
    }


    function register(AdminRegisterRequest $request, AdminRegisterService $adminRegisterService)
    {
        $data = $request->validated();
        $country = $adminRegisterService->findCountry($data['country_id']);
        $adminRegisterService->ValidPhoneForCountry($data['phone'], $country);
        try {
            DB::beginTransaction();

            $user = $adminRegisterService->createUser(
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['password'],
                $data['country_id']
            );
            $admin = $user->admin()->create([]);
            $store = $adminRegisterService->createStore(
                $admin,
                $data['store_name'],
                $data['link'],
                $country->currency_code
            );

            $adminRegisterService->createDefaultStoreCountry($store, $country);

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

            Log::error('Registration failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
