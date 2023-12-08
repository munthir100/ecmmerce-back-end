<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Entities\User;
use Modules\Store\Entities\Store;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Entities\UserType;
use Modules\Customer\Http\Requests\CustomerRegisterRequest;

class AuthController extends Controller
{
    function login(Request $request, Store $store)
    {
        $data = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $username = $data['username'];
        $password = $data['password'];

        $user = User::where(function ($query) use ($username) {
            $query->where('email', $username)
                ->orWhere('phone', $username)
                ->where('user_type_id', 2);
        })->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return $this->responseConflictError('invaild credantials');
        }
        $customer = $user->customer;
        if (!$customer || !$store->customers()->exists()) {
            return $this->responseConflictError('Customer does not have an account in this store');
        }

        $token = $user->createToken('token')->plainTextToken;
        return $this->responseSuccess('Login successful', ['token' => $token], 200);
    }

    function register(CustomerRegisterRequest $request, Store $store)
    {
        $data = $request->validated();
        $data['user_type_id'] = UserType::CUSTOMER;
        $data['password'] = Hash::make($data['password']);

        $email = $data['email'];
        $phone = $data['phone'];
        $exists = $store->customers()->whereHas('user', function ($query) use ($email, $phone) {
            $query->where('email', $email)
                ->orWhere('phone', $phone);
        })->exists();
        if ($exists) {
            return $this->responseConflictError('the email or phone is already exist for another customer');
        }
        $user = User::create($data);
        $customer = $user->customer()->create([
            'store_id' => $store->id
        ]);
        $customer->shoppingCart()->create();
        $token = $user->createToken('token')->plainTextToken;
        
        return $this->responseSuccess(
            message: 'register successfull',
            data: ['token' => $token],
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseSuccess('Successfully logged out');
    }
}
