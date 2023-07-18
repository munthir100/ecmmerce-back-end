<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Entities\User;
use Modules\Store\Entities\Store;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\MessageResponse;
use Modules\Customer\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;

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
            return new MessageResponse(message: 'invaild credantials');
        }
        $customer = $user->customer;
        if (!$customer || !$store->customers()->exists()) {
            return new MessageResponse('Customer does not have an account in this store');
        }

        $token = $user->createToken('token')->plainTextToken;
        return new MessageResponse('Login successful', ['token' => $token], 200);
    }

    function register(Request $request, Store $store)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => ['required'],
            'password' => 'required',
        ]);
        $data['user_type_id'] = 2;
        $data['password'] = Hash::make($data['password']);

        $email = $data['email'];
        $phone = $data['phone'];
        $exists = $store->customers()->whereHas('user', function ($query) use ($email, $phone) {
            $query->where('email', $email)
                ->orWhere('phone', $phone);
        })->exists();
        if ($exists) {
            return new MessageResponse('the email or phone is already exist');
        }
        $user = User::create($data);
        $customer = Customer::create([
            'user_id' => $user->id,
            'store_id' => $store->id
        ]);
        $token = $user->createToken('token')->plainTextToken;

        return new MessageResponse(
            message: 'register successfull',
            data: ['token' => $token],
            statusCode: 200
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return new MessageResponse('Successfully logged out');
    }
}
