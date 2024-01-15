<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Acl\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Entities\Customer;
use Modules\Admin\Http\Requests\CustomerRequest;
use Modules\Admin\Transformers\CustomerResource;
use Modules\Admin\Http\Requests\UpdateCustomerRequest;

class CustomerManagementController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('View-Customer');
        $customers = request()->store->customers()->useFilters()->with('user')->dynamicPaginate();

        return $this->responseSuccess(data:[CustomerResource::collection($customers)]);
    }

    public function store(CustomerRequest $request)
    {
        $this->authorize('Create-Customer');
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $exists = $this->checkCustomerExistenceInStore(request()->store, $data['email'], $data['phone']);
            if ($exists) {
                return $this->responseConflictError('The email or phone number already exists in this store.');
            }

            $data['user_type_id'] = 2;
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $data['user_id'] = $user->id;
            $data['store_id'] = request()->store->id;
            $customer = Customer::create($data);

            return $this->responseCreated(
                'Customer created successfully',
                ['customer' => new CustomerResource($customer)],
            );
        });
    }

    public function show($customerId)
    {
        $this->authorize('View-Customer');
        $customer = request()->store->customers()->findOrFail($customerId);

        return $this->responseSuccess(
            data: ['customer' => new CustomerResource($customer)]
        );
    }


    public function update(UpdateCustomerRequest $request, $customerId)
    {
        $this->authorize('Edit-Customer');
        $customer = request()->store->customers()->findOrFail($customerId);
        return DB::transaction(function () use ($request, $customer) {
            $data = $request->validated();
            $email = $data['email'] ?? null;
            $phone = $data['phone'] ?? null;

            if ($email || $phone) {
                $exists = $this->checkCustomerExistenceInStore(request()->store, $email, $phone, $customer->user_id);
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
        $this->authorize('Delete-Customer');
        $customer = request()->store->customers()->findOrFail($customerId);
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
