<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StoreService;
use Illuminate\Support\Carbon;
use Modules\Acl\Entities\User;
use Illuminate\Support\Facades\DB;
use Modules\Acl\Entities\UserType;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Shipping\Entities\Country;
use App\Services\Admin\AdminRegisterService;
use Modules\Admin\Transformers\UserResource;
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
            return $this->responseUnAuthenticated('user not found');
        }

        $token = $user->createToken('token')->plainTextToken;

        return $this->responseSuccess(message:'login successfull',data:['token' => $token]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseSuccess('Successfully logged out');
    }


    function register(
        AdminRegisterRequest $request,
        AdminRegisterService $adminRegisterService,
        StoreService $storeService
    ) {
        $data = $request->validated();
        $country = Country::findOrFail($data['country_id']);
        $adminRegisterService->ValidPhoneForCountry($data['phone'],$country);
        try {
            DB::beginTransaction();

            $user = $adminRegisterService->createUser(
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['password'],
                $data['country_id']
            );
            $adminRegisterService->setPermissionsToUser($user);
            $admin = $user->admin()->create([]);
            $store = $adminRegisterService->createStore(
                $admin,
                $data['store_name'],
                $data['link'],
                $country->currency_code
            );
            $adminRegisterService->createDefaultStoreCountry($store, $country);
            $storeService->createFreeTrial($store);


            DB::commit();

            $jsonData = [
                'user' => new UserResource($user),
                'admin' => $admin,
                'store' => $store,
                'token' => $user->createToken('accessToken')->plainTextToken
            ];

            return $this->responseSuccess(message: 'Registration success', data: $jsonData);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
