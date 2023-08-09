<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Acl\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\MessageResponse;
use App\Traits\ModelsForAdmin;
use Modules\Customer\Entities\Customer;
use Modules\Admin\Http\Requests\CustomerRequest;
use Modules\Admin\Transformers\CustomerResource;
use Modules\Admin\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    use ModelsForAdmin;

    public function index()
    {
        $term = request()->get('term', '');
        $perPage = request()->get('perPage', 25);
        $store = request()->user()->admin->store;

        $customers = $store->customers() // correct but need to review
            ->with('user')
            ->where(function ($query) use ($term) {
                if (!empty($term)) {
                    $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . $term . '%');
                }
            })
            ->paginate($perPage);

        return new MessageResponse(
            data: ['customers' => CustomerResource::collection($customers)],
            statusCode: 200
        );
    }

    public function store(CustomerRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $store = $request->user()->admin->store;

            $exists = $this->checkCustomerExistenceInStore($store, $data['email'], $data['phone']);
            if ($exists) {
                return new MessageResponse(
                    message: 'The email or phone number already exists in this store.',
                    statusCode: 409
                );
            }

            $data['user_type_id'] = 2;
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $data['user_id'] = $user->id;
            $data['store_id'] = $store->id;
            $customer = Customer::create($data);

            return new MessageResponse(
                message: 'Customer created successfully',
                data: ['customer' => new CustomerResource($customer)],
                statusCode: 200
            );
        });
    }

    public function show($customerId)
    {
        $customer = $this->findAdminModel(Customer::class, $customerId);
        $store = request()->user()->admin->store;

        if (!$store->customers()->where('id', $customer->id)->exists()) {
            return new MessageResponse(
                message: 'Unauthorized access to customer data.',
                statusCode: 401
            );
        }

        return new MessageResponse(
            data: ['customer' => new CustomerResource($customer)],
            statusCode: 200
        );
    }


    public function update(UpdateCustomerRequest $request, $customerId)
    {
        $customer = $this->findAdminModel(Customer::class, $customerId);
        return DB::transaction(function () use ($request, $customer) {
            $data = $request->validated();
            $store = $request->user()->admin->store;
            $email = $data['email'] ?? null;
            $phone = $data['phone'] ?? null;

            if ($email || $phone) {
                $exists = $this->checkCustomerExistenceInStore($store, $email, $phone, $customer->user_id);
                if ($exists) {
                    return new MessageResponse(
                        message: 'The email or phone number already exists in this store.',
                        statusCode: 409
                    );
                }
            }
            $user = $customer->user;
            $user->update($data);
            $customer->update($data);

            return new MessageResponse(
                'Customer data updated',
                ['customer' => new CustomerResource($customer)],
                statusCode: 200
            );
        });
    }


    public function destroy($customerId)
    {
        $customer = $this->findAdminModel(Customer::class, $customerId);
        $store = request()->user()->admin->store;

        if (!$store->customers()->where('id', $customer->id)->exists()) {
            return new MessageResponse(
                message: 'Unauthorized access to customer data.',
                statusCode: 401
            );
        }

        $customer->user->delete();

        return new MessageResponse(
            message: 'Customer deleted',
            data: ['customer' => new CustomerResource($customer)],
            statusCode: 200
        );
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
