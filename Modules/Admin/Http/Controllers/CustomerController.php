<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Acl\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Traits\ModelsForAdmin;
use Essa\APIToolKit\Api\ApiResponse;
use Modules\Customer\Entities\Customer;
use Modules\Admin\Http\Requests\CustomerRequest;
use Modules\Admin\Transformers\CustomerResource;
use Modules\Admin\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    use ModelsForAdmin, ApiResponse;

    public function index()
    {

        $store = auth()->user()->admin->store;

        $customers = $store->customers()->useFilters()->with('user')->dynamicPaginate();

        return ['customer' => CustomerResource::collection($customers)];
    }

    public function store(CustomerRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $store = $request->user()->admin->store;

            $exists = $this->checkCustomerExistenceInStore($store, $data['email'], $data['phone']);
            if ($exists) {
                return $this->responseConflictError('The email or phone number already exists in this store.');
            }

            $data['user_type_id'] = 2;
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $data['user_id'] = $user->id;
            $data['store_id'] = $store->id;
            $customer = Customer::create($data);

            return $this->responseCreated(
                'Customer created successfully',
                ['customer' => new CustomerResource($customer)],
            );
        });
    }

    public function show($customerId)
    {
        $customer = $this->findAdminModel(auth()->user()->admin, Customer::class, $customerId);

        return $this->responseSuccess(
            data: ['customer' => new CustomerResource($customer)]
        );
    }


    public function update(UpdateCustomerRequest $request, $customerId)
    {
        $customer = $this->findAdminModel(auth()->user()->admin, Customer::class, $customerId);
        return DB::transaction(function () use ($request, $customer) {
            $data = $request->validated();
            $store = $request->user()->admin->store;
            $email = $data['email'] ?? null;
            $phone = $data['phone'] ?? null;

            if ($email || $phone) {
                $exists = $this->checkCustomerExistenceInStore($store, $email, $phone, $customer->user_id);
                if ($exists) {
                    return $this->responseUnprocessable(
                        message: 'The email or phone number already exists in this store.',
                    );
                }
            }
            $user = $customer->user;
            $user->update($data);
            $customer->update($data);

            return $this->responseSuccess(
                'Customer data updated',
                ['customer' => new CustomerResource($customer)],
            );
        });
    }


    public function destroy($customerId)
    {
        $customer = $this->findAdminModel(auth()->user()->admin, Customer::class, $customerId);
        $customer->user->delete();

        return $this->responseSuccess('Customer deleted');
    }


    private function checkCustomerExistenceInStore($store, $email, $phone, $customerId = null)
    {
        $query = $store->customers()->whereHas('user', function ($query) use ($email, $phone) {
            if ($email !== null) {
                $query->where('email', $email);
            }
            if ($phone !== null) {
                $query->orWhere('phone', $phone);
            }
        });

        if ($customerId !== null) {
            $query->where('id', '!=', $customerId);
        }

        return $query->exists();
    }
}
