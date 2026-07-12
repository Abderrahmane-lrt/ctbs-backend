<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        $genres = ['Action', 'Comedy', 'Drama', 'Horror', 'Sci-Fi', 'Thriller', 'Animation', 'Romance'];

        return [
            'title' => fake()->unique()->words(3, true),
            'description' => fake()->paragraph(),
            'duration_minutes' => fake()->numberBetween(85, 195),
            'genre' => fake()->randomElement($genres),
            'poster_url' => fake()->optional(0.7)->imageUrl(640, 480, 'movies', true),
        ];
    }
}
