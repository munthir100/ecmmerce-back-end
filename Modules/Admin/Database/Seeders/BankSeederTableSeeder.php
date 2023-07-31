<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\Bank;

class BankSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create([
            'name' => 'Fake Khartoum Bank'
        ]);
        Bank::create([
            'name' => 'Fake Faisal Islamic Bank'
        ]);
    }
}
