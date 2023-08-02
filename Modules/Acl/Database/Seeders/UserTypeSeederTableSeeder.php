<?php

namespace Modules\Acl\Database\Seeders;

use Modules\Acl\Entities\User;
use Illuminate\Database\Seeder;
use Modules\Admin\Entities\Admin;
use Modules\Store\Entities\Store;
use Illuminate\Support\Facades\DB;
use Modules\Acl\Entities\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserTypeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userTypes = [
            ['name' => 'Admin'],
            ['name' => 'Customer'],
            ['name' => 'Seller'],
        ];

        DB::table('user_types')->insert($userTypes);
    }
}
