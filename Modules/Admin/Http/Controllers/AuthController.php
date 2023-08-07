<?php

namespace Modules\Admin\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Acl\Entities\User;
use Modules\Admin\Entities\Admin;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidPhoneForCountry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Shipping\Entities\Country;
use App\Http\Responses\MessageResponse;
use App\Services\AdminRegisterService;
use Modules\Admin\Rules\UniqueEmailForAdmin;
use Modules\Admin\Rules\UniquePhoneForAdmin;
use Modules\Acl\Transformers\AuthenticatedUserResource;
use Modules\Admin\Http\Requests\AdminRegisterRequest;

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
                $user->id,
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
