<?php

namespace Modules\Store\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Store\Entities\StoreFactory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}

