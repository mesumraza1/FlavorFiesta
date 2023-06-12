<?php

namespace Database\Factories;

use App\Models\categories;
use Illuminate\Database\Eloquent\Factories\Factory;

class categoriesFactory extends Factory
{
    protected $model = categories::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
