<?php

namespace Modules\Acl\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => config('auth.defaults.guard'),
        ]);
        Role::create([
            'name' => 'customer',
            'guard_name' => config('auth.defaults.guard'),
        ]);
        Role::create([
            'name' => 'seller',
            'guard_name' => config('auth.defaults.guard'),
        ]);
    }
}
