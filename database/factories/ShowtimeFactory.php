<?php

namespace Database\Factories;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Showtime>
 */
class ShowtimeFactory extends Factory
{
    protected $model = Showtime::class;

    public function definition(): array
    {
        $hour = fake()->numberBetween(10, 22);

        return [
            'movie_id' => Movie::factory(),
            'cinema_id' => Cinema::factory(),
            'room_name' => 'Room '.fake()->numberBetween(1, 12),
            'capacity' => fake()->numberBetween(50, 300),
            'start_time' => fake()->dateTimeBetween('+1 day', '+30 days')->setTime($hour, 0),
            'price' => fake()->randomFloat(2, 30, 150),
        ];
    }
}
