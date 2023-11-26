<?php

namespace Database\Factories\Tenant\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

class PromocodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Tenant\Models\Promocode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->name,
            'type' => ['flat', 'percentage'][rand(0, 1)],
            'reward' => $this->faker->numberBetween($min = 10, $max = 80),
            'data' => [$this->faker->sentence($nbWords = 6, $variableNbWords = true)],
            'is_disposable' => $this->faker->boolean,
            'commence_at' => $this->faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now'),
            'expires_at' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '+1 months'),
        ];
    }
}
