<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Entities\Status;
use Illuminate\Support\Facades\Config;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = Config::get('statuses');

        foreach ($statuses as $category => $categoryStatuses) {
            foreach ($categoryStatuses as $name) {
                Status::create([
                    'name' => $name,
                ]);
            }
        }
    }
}
