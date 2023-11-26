<?php

namespace Database\Factories\Tenant\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Tenant\Models\Quotation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->jobTitle;

        return [
            'application_id' => 1,
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
        ];
    }
}
