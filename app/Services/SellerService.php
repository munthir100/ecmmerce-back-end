<?php

namespace App\Services;

use Modules\Acl\Entities\User;
use Modules\Acl\Entities\UserType;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Permission;
use Modules\Admin\Transformers\SellerResource;

class SellerService
{
    public function validateEmail($email,$admin)
    {
        $existingSeller = $admin->sellers()->whereExists(function ($query) use ($email) {
            $query->selectRaw(1)
                ->from('users')
                ->where('email', $email)
                ->whereColumn('users.id', 'sellers.user_id')
                ->limit(1);
        })->exists();

        if ($existingSeller) {
            return Response::json(['message' => 'This seller email is already registered for this store'], 400)->throwResponse();
        }

        return null;
    }
    public function createSellerUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type_id' => UserType::SELLER,
        ]);
    }

    public function createSeller($admin, $store, $user)
    {
        $admin->sellers()->create([
            'user_id' => $user->id,
            'store_id' => $store->id,
        ]);
    }

    public function assignRoleAndPermissions(array $data, $user)
    {
        $role = Role::firstOrCreate([
            'name' => $data['role'],
            'guard_name' => 'api'
        ]);

        $selectedPermissions = $data['permissions'] ?? [];
        $permissions = Permission::whereIn('name', $selectedPermissions)->get();

        $user->assignRole($role)->syncPermissions($permissions);

        return [$role, $permissions];
    }

    public function prepareResponseData($user, $role, $permissions)
    {
        return [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role->name,
                'permissions' => $permissions->pluck('name'),
            ],
            'seller' => new SellerResource($user->seller),
        ];
    }
}
