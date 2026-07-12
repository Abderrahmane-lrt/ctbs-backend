<?php

namespace Database\Factories;

use App\Models\Cinema;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cinema>
 */
class CinemaFactory extends Factory
{
    protected $model = Cinema::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'name' => fake()->company().' Cinema',
            'city' => fake()->city(),
            'address' => fake()->address(),
        ];
    }
}
