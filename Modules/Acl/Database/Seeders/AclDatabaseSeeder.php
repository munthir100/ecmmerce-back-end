<?php

namespace Modules\Acl\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Acl\Database\Seeders\PermissionsTableSeeder;

class AclDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTypeSeederTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
    }
}
