<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // admin test 
        User::create([
            "name" => "Abdo Admin",
            "email" => "admin@ctbs.ma",
            "password" => Hash::make('admin123'),
            "role" => "admin"
        ]);
        // agent test

        User::create([
            "name" => "azize chakir",
            "email" => "azize.chakir@gmail.com",
            "password" => "azize123",
            "role" => "agent"
        ]);


        $movie = Movie::create([
            'title' => 'Inception',
            'description' => 'Un film de science-fiction incroyable.',
            'duration_minutes' => 120,
            'genre' => 'Sci-Fi'
        ]);

        Showtime::create([
            'movie_id' => $movie->id,
            'room_name' => 'Salle IMAX',
            'start_time' => '2026-07-11 21:00:00',
            'capacity' => 2,
            'price' => 49.99
        ]);
    }
}
