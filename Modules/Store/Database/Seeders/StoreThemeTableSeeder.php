<?php

namespace Modules\Store\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Store\Entities\StoreTheme;

class StoreThemeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreTheme::create([
            'name' => 'default',
            'price' => 0
        ]);
        StoreTheme::create([
            'name' => 'first',
            'price' => 25
        ]);
    }
}
