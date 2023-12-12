<?php

namespace App\Http\Controllers\superAdmin;

use Stripe\Customer;
use Illuminate\Http\Request;
use Modules\Acl\Entities\User;
use App\Http\Controllers\Controller;
use Modules\Admin\Transformers\UserResource;

class UserController extends Controller
{
    function index(Request $request)
    {
        $users = User::get();

        return $this->responseSuccess(data:[UserResource::collection($users)]);
    }
}
