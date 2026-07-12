<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with('movie');

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        $showtimes = $query->orderBy('start_time', 'asc')->get();

        return response()->json([
            'showtimes' => $showtimes
        ], 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'movie_id'   => 'required|exists:movies,id',
            'city'       => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'room_name'  => 'required|string|max:255',
            'capacity'   => 'required|integer|min:1',
            'start_time' => 'required|date|after:now',
            'price'      => 'required|numeric|min:0',
        ]);

        $showtime = Showtime::create($fields);

        return response()->json([
            'message'  => 'Showtime scheduled successfully.',
            'showtime' => $showtime->load('movie')
        ], 201);
    }

    public function show(Showtime $showtime)
    {
        return response()->json([
            'showtime' => $showtime->load('movie')
        ], 200);
    }

    public function update(Request $request, Showtime $showtime)
    {
        $fields = $request->validate([
            'movie_id'   => 'sometimes|exists:movies,id',
            'city'       => 'sometimes|string|max:255',
            'location'   => 'sometimes|string|max:255',
            'room_name'  => 'sometimes|string|max:255',
            'capacity'   => 'sometimes|integer|min:1',
            'start_time' => 'sometimes|date|after:now',
            'price'      => 'sometimes|numeric|min:0',
        ]);

        $showtime->update($fields);

        return response()->json([
            'message'  => 'Showtime updated successfully.',
            'showtime' => $showtime->load('movie')
        ], 200);
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return response()->json([
            'message' => 'Showtime deleted successfully.'
        ], 200);
    }
}