<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $adminYoussef = User::create([
            'name' => 'Youssef Bennani',
            'email' => 'youssef@megarama.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $adminFatima = User::create([
            'name' => 'Fatima El Fassi',
            'email' => 'fatima@cineatlas.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::factory()->agent()->create([
            'name' => 'Karim Tazi',
            'email' => 'karim@megarama.ma',
            'password' => Hash::make('password'),
        ]);

        User::factory()->client()->create([
            'name' => 'Sara Alaoui',
            'email' => 'sara.alaoui@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $megaramaCasablanca = Cinema::create([
            'owner_id' => $adminYoussef->id,
            'name' => 'Megarama',
            'city' => 'Casablanca',
            'address' => 'Boulevard Massira Al Khadra, Casablanca',
        ]);

        $megaramaMarrakech = Cinema::create([
            'owner_id' => $adminYoussef->id,
            'name' => 'Megarama',
            'city' => 'Marrakech',
            'address' => 'Avenue Mohammed V, Marrakech',
        ]);

        $cineAtlasRabat = Cinema::create([
            'owner_id' => $adminFatima->id,
            'name' => 'CineAtlas',
            'city' => 'Rabat',
            'address' => 'Avenue Fal Ouled Oumerane, Rabat',
        ]);

        $movieTitles = [
            ['title' => 'Dune: Part Three', 'description' => 'Paul Atreides embraces his destiny as the Kwisatz Haderach in the final chapter of the saga.', 'duration_minutes' => 155, 'genre' => 'Sci-Fi'],
            ['title' => 'The Batman II', 'description' => 'Batman faces a new threat lurking in the shadows of Gotham City.', 'duration_minutes' => 175, 'genre' => 'Action'],
            ['title' => 'Inside Out 3', 'description' => 'Riley enters her twenties and a whole new set of emotions takes the wheel.', 'duration_minutes' => 100, 'genre' => 'Animation'],
            ['title' => 'Oppenheimer II', 'description' => 'The aftermath of the atomic age and its impact on global politics.', 'duration_minutes' => 180, 'genre' => 'Drama'],
            ['title' => 'A Quiet Place: Day One', 'description' => 'The beginning of the invasion in New York City.', 'duration_minutes' => 95, 'genre' => 'Horror'],
        ];

        $movieIds = [];
        foreach ($movieTitles as $data) {
            $movieIds[] = Movie::create($data)->id;
        }

        $showtimes = [];
        $rooms = ['Room 1', 'Room 2', 'Room 3', 'IMAX', 'VIP'];
        $cinemas = [$megaramaCasablanca, $megaramaMarrakech, $cineAtlasRabat];

        foreach ($cinemas as $cinema) {
            foreach ($movieIds as $index => $movieId) {
                $showtimes[] = [
                    'movie_id' => $movieId,
                    'cinema_id' => $cinema->id,
                    'room_name' => $rooms[$index % count($rooms)],
                    'capacity' => [120, 80, 200, 350, 60][$index % 5],
                    'start_time' => now()->addDays($index + 1)->setTime(19 + ($index % 3), 0),
                    'price' => [50.00, 65.00, 45.00, 120.00, 35.00][$index % 5],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Showtime::insert($showtimes);
    }
}
